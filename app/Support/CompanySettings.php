<?php

namespace App\Support;

use App\Models\Setting;

class CompanySettings
{
    public static function get(?int $companyId, string $key, mixed $default = null): mixed
    {
        if ($companyId === null) {
            return $default;
        }

        $value = Setting::query()
            ->where('company_id', $companyId)
            ->where('key', $key)
            ->value('value');

        return $value !== null ? $value : $default;
    }

    public static function otpLoginEnabled(?int $companyId): bool
    {
        return (string) self::get($companyId, 'otp_login_enabled', '1') === '1';
    }

    public static function otpChannel(?int $companyId): string
    {
        $channel = (string) self::get($companyId, 'otp_channel', 'both');

        return in_array($channel, ['sms', 'whatsapp', 'both'], true) ? $channel : 'both';
    }

    public static function otpWhatsAppProvider(?int $companyId): string
    {
        $provider = (string) self::get($companyId, 'otp_whatsapp_provider', 'meta');

        return in_array($provider, ['meta', 'callmebot'], true) ? $provider : 'meta';
    }

    public static function usesCallMeBot(?int $companyId): bool
    {
        if ($companyId === null) {
            return false;
        }

        if (self::otpWhatsAppProvider($companyId) !== 'callmebot') {
            return false;
        }

        return in_array(self::otpChannel($companyId), ['whatsapp', 'both'], true);
    }
}
