<?php

namespace App\Services\Messaging;

use App\Support\CompanySettings;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpMessenger
{
    public function send(int $companyId, string $phone, string $message, ?string $callmebotApiKey = null): bool
    {
        $channel = CompanySettings::otpChannel($companyId);
        $sent = false;

        if (in_array($channel, ['whatsapp', 'both'], true)) {
            $sent = $this->sendWhatsApp($companyId, $phone, $message, $callmebotApiKey) || $sent;
        }

        if (in_array($channel, ['sms', 'both'], true)) {
            $sent = $this->sendSms($companyId, $phone, $message) || $sent;
        }

        return $sent;
    }

    private function sendWhatsApp(int $companyId, string $phone, string $message, ?string $callmebotApiKey = null): bool
    {
        if (CompanySettings::otpWhatsAppProvider($companyId) === 'callmebot') {
            return $this->sendCallMeBot($companyId, $phone, $message, $callmebotApiKey);
        }

        return $this->sendMetaWhatsApp($companyId, $phone, $message);
    }

    private function sendCallMeBot(int $companyId, string $phone, string $message, ?string $callmebotApiKey): bool
    {
        $apiKey = trim((string) $callmebotApiKey);
        if ($apiKey === '') {
            Log::warning('CallMeBot OTP skipped: employee API key missing', [
                'company_id' => $companyId,
            ]);

            return false;
        }

        $internationalPhone = PhoneNumber::toInternational($phone);
        if ($internationalPhone === '') {
            return false;
        }

        try {
            $response = Http::timeout(20)->get('https://api.callmebot.com/whatsapp.php', [
                'phone' => $internationalPhone,
                'text' => $message,
                'apikey' => $apiKey,
            ]);

            $body = strtolower(trim($response->body()));

            if ($response->successful() && $this->callMeBotResponseLooksSuccessful($body)) {
                return true;
            }

            Log::warning('CallMeBot OTP send failed', [
                'company_id' => $companyId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('CallMeBot OTP exception', [
                'company_id' => $companyId,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    private function callMeBotResponseLooksSuccessful(string $body): bool
    {
        if ($body === '') {
            return false;
        }

        if (str_contains($body, 'error') || str_contains($body, 'invalid') || str_contains($body, 'fail')) {
            return false;
        }

        return str_contains($body, 'queue')
            || str_contains($body, 'sent')
            || str_contains($body, 'message added')
            || str_contains($body, 'ok');
    }

    private function sendMetaWhatsApp(int $companyId, string $phone, string $message): bool
    {
        $token = trim((string) CompanySettings::get($companyId, 'otp_whatsapp_token', ''));
        $phoneId = trim((string) CompanySettings::get($companyId, 'otp_whatsapp_phone_id', ''));

        if ($token === '' || $phoneId === '') {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(20)
                ->post("https://graph.facebook.com/v21.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('WhatsApp OTP send failed', [
                'company_id' => $companyId,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp OTP exception', ['company_id' => $companyId, 'error' => $e->getMessage()]);
        }

        return false;
    }

    private function sendSms(int $companyId, string $phone, string $message): bool
    {
        $url = trim((string) CompanySettings::get($companyId, 'otp_sms_api_url', ''));
        $apiKey = trim((string) CompanySettings::get($companyId, 'otp_sms_api_key', ''));
        $method = strtolower((string) CompanySettings::get($companyId, 'otp_sms_method', 'post'));

        if ($url === '') {
            return false;
        }

        $replacements = [
            '{phone}' => $phone,
            '{message}' => $message,
            '{api_key}' => $apiKey,
            '{sender}' => trim((string) CompanySettings::get($companyId, 'otp_sms_sender', '')),
        ];

        $resolvedUrl = str_replace(array_keys($replacements), array_values($replacements), $url);

        try {
            $request = Http::timeout(20);

            if ($apiKey !== '' && ! str_contains($resolvedUrl, $apiKey)) {
                $request = $request->withHeaders(['Authorization' => 'Bearer '.$apiKey]);
            }

            $response = $method === 'get'
                ? $request->get($resolvedUrl)
                : $request->asForm()->post($resolvedUrl, [
                    'phone' => $phone,
                    'to' => $phone,
                    'message' => $message,
                    'text' => $message,
                    'api_key' => $apiKey,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('SMS OTP send failed', [
                'company_id' => $companyId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('SMS OTP exception', ['company_id' => $companyId, 'error' => $e->getMessage()]);
        }

        return false;
    }
}
