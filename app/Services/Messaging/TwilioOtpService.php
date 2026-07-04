<?php

namespace App\Services\Messaging;

use App\Support\CompanySettings;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioOtpService
{
    public function send(int $companyId, string $phone, string $message): bool
    {
        $sid = trim((string) CompanySettings::get($companyId, 'otp_twilio_account_sid', ''));
        $token = trim((string) CompanySettings::get($companyId, 'otp_twilio_auth_token', ''));
        $from = trim((string) CompanySettings::get($companyId, 'otp_twilio_from_number', ''));

        if ($sid === '' || $token === '' || $from === '') {
            Log::warning('Twilio OTP not configured', ['company_id' => $companyId]);

            return false;
        }

        $to = PhoneNumber::toInternational($phone);
        if ($to === '') {
            return false;
        }

        try {
            $client = new Client($sid, $token);
            $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Twilio OTP send failed', [
                'company_id' => $companyId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function isConfigured(int $companyId): bool
    {
        return trim((string) CompanySettings::get($companyId, 'otp_twilio_account_sid', '')) !== ''
            && trim((string) CompanySettings::get($companyId, 'otp_twilio_auth_token', '')) !== ''
            && trim((string) CompanySettings::get($companyId, 'otp_twilio_from_number', '')) !== '';
    }
}
