<?php

namespace App\Services\Messaging;

use App\Support\CompanySettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpMessenger
{
    public function send(int $companyId, string $phone, string $message): bool
    {
        $channel = CompanySettings::otpChannel($companyId);
        $sent = false;

        if (in_array($channel, ['whatsapp', 'both'], true)) {
            $sent = $this->sendWhatsApp($companyId, $phone, $message) || $sent;
        }

        if (in_array($channel, ['sms', 'both'], true)) {
            $sent = $this->sendSms($companyId, $phone, $message) || $sent;
        }

        return $sent;
    }

    private function sendWhatsApp(int $companyId, string $phone, string $message): bool
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
