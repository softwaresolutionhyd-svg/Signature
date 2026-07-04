<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use App\Services\Messaging\OtpMessenger;
use App\Support\CompanySettings;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LoginOtpService
{
    private const CACHE_PREFIX = 'login_otp:';

    private const RESEND_PREFIX = 'login_otp_resend:';

    private const TTL_SECONDS = 600;

    private const MAX_VERIFY_ATTEMPTS = 5;

    public function __construct(
        private readonly OtpMessenger $messenger
    ) {}

    public function requiresOtp(User $user): bool
    {
        if ($user->isPlatformSuperAdmin()) {
            return false;
        }

        if ($user->company_id === null) {
            return false;
        }

        return CompanySettings::otpLoginEnabled((int) $user->company_id);
    }

    public function resolvePhoneForUser(User $user): ?string
    {
        if ($user->isPlatformSuperAdmin()) {
            return null;
        }

        $employee = Employee::query()
            ->where('user_id', $user->id)
            ->when($user->company_id, fn ($q) => $q->where('company_id', $user->company_id))
            ->first();

        $phone = trim((string) ($employee?->phone ?? ''));

        if ($phone === '' && $user->company_id !== null) {
            $phone = trim((string) CompanySettings::get((int) $user->company_id, 'company_phone', ''));
        }

        $normalized = PhoneNumber::normalize($phone);

        return $normalized !== '' ? $normalized : null;
    }

    public function resolveCallMeBotKeyForUser(User $user): ?string
    {
        $employee = Employee::query()
            ->where('user_id', $user->id)
            ->when($user->company_id, fn ($q) => $q->where('company_id', $user->company_id))
            ->first();

        $key = trim((string) ($employee?->callmebot_api_key ?? ''));

        return $key !== '' ? $key : null;
    }

    /**
     * @return array{token: string, masked_phone: string}
     */
    public function startChallenge(User $user, bool $remember, string $phone): array
    {
        $code = (string) random_int(100000, 999999);
        $token = Str::random(64);

        Cache::put(self::CACHE_PREFIX.$token, [
            'user_id' => $user->id,
            'remember' => $remember,
            'code_hash' => hash('sha256', $code),
            'phone' => $phone,
            'company_id' => (int) $user->company_id,
            'attempts' => 0,
        ], self::TTL_SECONDS);

        $companyName = (string) CompanySettings::get((int) $user->company_id, 'company_name', config('app.name', 'Signature'));
        $message = "{$companyName} login code: {$code}. Valid for 10 minutes. Do not share this code.";

        $callmebotKey = CompanySettings::usesCallMeBot((int) $user->company_id)
            ? $this->resolveCallMeBotKeyForUser($user)
            : null;

        if (CompanySettings::usesCallMeBot((int) $user->company_id) && $callmebotKey === null) {
            Cache::forget(self::CACHE_PREFIX.$token);
            throw new \RuntimeException('CallMeBot API key missing on employee record.');
        }

        if (! $this->messenger->send((int) $user->company_id, $phone, $message, $callmebotKey)) {
            Cache::forget(self::CACHE_PREFIX.$token);
            throw new \RuntimeException('OTP could not be sent.');
        }

        return [
            'token' => $token,
            'masked_phone' => PhoneNumber::mask($phone),
        ];
    }

    public function canResend(string $token): bool
    {
        return ! Cache::has(self::RESEND_PREFIX.$token);
    }

    public function markResent(string $token): void
    {
        Cache::put(self::RESEND_PREFIX.$token, true, 60);
    }

    /**
     * @return array{user: User, remember: bool}|null
     */
    public function verify(string $token, string $code): ?array
    {
        $key = self::CACHE_PREFIX.$token;
        $payload = Cache::get($key);

        if (! is_array($payload)) {
            return null;
        }

        $attempts = (int) ($payload['attempts'] ?? 0);
        if ($attempts >= self::MAX_VERIFY_ATTEMPTS) {
            Cache::forget($key);

            return null;
        }

        $payload['attempts'] = $attempts + 1;
        Cache::put($key, $payload, self::TTL_SECONDS);

        if (! hash_equals((string) $payload['code_hash'], hash('sha256', trim($code)))) {
            return null;
        }

        $user = User::query()->find($payload['user_id'] ?? 0);
        if (! $user) {
            Cache::forget($key);

            return null;
        }

        Cache::forget($key);
        Cache::forget(self::RESEND_PREFIX.$token);

        return [
            'user' => $user,
            'remember' => (bool) ($payload['remember'] ?? false),
        ];
    }

    public function regenerateAndSend(string $token): ?string
    {
        $key = self::CACHE_PREFIX.$token;
        $payload = Cache::get($key);

        if (! is_array($payload)) {
            return null;
        }

        $user = User::query()->find($payload['user_id'] ?? 0);
        if (! $user) {
            Cache::forget($key);

            return null;
        }

        $phone = (string) ($payload['phone'] ?? '');
        if ($phone === '') {
            return null;
        }

        Cache::forget($key);

        $challenge = $this->startChallenge($user, (bool) ($payload['remember'] ?? false), $phone);

        return $challenge['token'];
    }
}
