<?php

declare(strict_types=1);

use Auth0\Laravel\Configuration;
use Auth0\SDK\Configuration\SdkConfiguration;

$domain = trim((string) env('AUTH0_DOMAIN', ''));
$domain = preg_replace('#^https?://#i', '', rtrim($domain, '/'));

$clientId = trim((string) env('AUTH0_CLIENT_ID', ''));
$clientSecret = trim((string) env('AUTH0_CLIENT_SECRET', ''));

$redirectUri = trim((string) env('AUTH0_REDIRECT_URI', ''));
if ($redirectUri === '') {
    $redirectUri = rtrim((string) env('APP_URL', ''), '/').'/callback';
}

$appKey = (string) env('APP_KEY', '');
$cookieSecret = trim((string) env('AUTH0_COOKIE_SECRET', ''));
if ($cookieSecret === '' || strlen($cookieSecret) < 32) {
    // Auth0 SDK expects a stable string secret; use APP_KEY as-is (incl. base64: prefix).
    $cookieSecret = strlen($appKey) >= 32 ? $appKey : hash('sha256', $appKey !== '' ? $appKey : 'signature-auth0');
}

$enabled = filter_var(env('AUTH0_ENABLED', false), FILTER_VALIDATE_BOOL)
    && $domain !== ''
    && $clientId !== ''
    && $clientSecret !== '';

return Configuration::VERSION_2 + [
    'enabled' => $enabled,
    'domain' => $domain,
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'cookie_secret' => $cookieSecret,

    'registerGuards' => false,
    'registerMiddleware' => false,
    'registerAuthenticationRoutes' => false,
    'configurationPath' => null,

    'guards' => [
        'default' => [
            Configuration::CONFIG_STRATEGY => SdkConfiguration::STRATEGY_REGULAR,
            Configuration::CONFIG_DOMAIN => $domain,
            Configuration::CONFIG_CLIENT_ID => $clientId,
            Configuration::CONFIG_CLIENT_SECRET => $clientSecret,
            Configuration::CONFIG_SCOPE => ['openid', 'profile', 'email'],
            Configuration::CONFIG_USE_PKCE => true,
            Configuration::CONFIG_RESPONSE_MODE => 'query',
            Configuration::CONFIG_RESPONSE_TYPE => 'code',
        ],

        'api' => [
            Configuration::CONFIG_STRATEGY => SdkConfiguration::STRATEGY_API,
        ],

        'web' => [
            Configuration::CONFIG_STRATEGY => SdkConfiguration::STRATEGY_REGULAR,
            Configuration::CONFIG_DOMAIN => $domain,
            Configuration::CONFIG_CLIENT_ID => $clientId,
            Configuration::CONFIG_CLIENT_SECRET => $clientSecret,
            Configuration::CONFIG_COOKIE_SECRET => $cookieSecret,
            Configuration::CONFIG_REDIRECT_URI => $redirectUri,
            Configuration::CONFIG_SCOPE => ['openid', 'profile', 'email'],
            Configuration::CONFIG_USE_PKCE => true,
            Configuration::CONFIG_RESPONSE_MODE => 'query',
            Configuration::CONFIG_RESPONSE_TYPE => 'code',
        ],
    ],

    'routes' => [
        Configuration::CONFIG_ROUTE_INDEX => '/',
        Configuration::CONFIG_ROUTE_CALLBACK => '/callback',
        Configuration::CONFIG_ROUTE_LOGIN => '/auth0/login',
        Configuration::CONFIG_ROUTE_AFTER_LOGIN => '/dashboard',
        Configuration::CONFIG_ROUTE_LOGOUT => '/auth0/logout',
        Configuration::CONFIG_ROUTE_AFTER_LOGOUT => '/login',
    ],
];
