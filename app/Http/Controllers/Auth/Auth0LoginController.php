<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth0\Laravel\Controllers\LoginController as SdkLoginController;
use Auth0\Laravel\Guards\GuardAbstract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Auth0LoginController extends Controller
{
    public function __invoke(Request $request): Response
    {
        auth()->shouldUse('auth0-session');

        if ($configError = $this->validateAuth0Config($request)) {
            return redirect()->route('login')->withErrors(['login' => $configError]);
        }

        $guard = auth()->guard();
        if (! $guard instanceof GuardAbstract) {
            return redirect()->route('login')->withErrors([
                'login' => 'Auth0 guard configure nahi hai. Server par auth0/login package aur PHP 8.3 check karein.',
            ]);
        }

        try {
            return app(SdkLoginController::class)($request);
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors([
                'login' => $this->friendlyMessage($e).' ['.$this->shortError($e).']',
            ]);
        }
    }

    private function validateAuth0Config(Request $request): ?string
    {
        if (! filled(config('auth0.domain'))) {
            return 'AUTH0_DOMAIN missing hai. Example: signature-ss.us.auth0.com (bina https://). Phir: php artisan config:cache';
        }

        if (! filled(config('auth0.client_id'))) {
            return 'AUTH0_CLIENT_ID missing hai. Auth0 Dashboard → Application → Settings se copy karein.';
        }

        if (! filled(config('auth0.client_secret'))) {
            return 'AUTH0_CLIENT_SECRET missing hai. Auth0 Dashboard → Application → Settings se copy karein.';
        }

        if (! filled(config('auth0.redirect_uri'))) {
            return 'AUTH0_REDIRECT_URI set karein: https://signature.softwaresolutions.pk/callback';
        }

        if (strlen(auth0_cookie_secret()) < 32) {
            return 'APP_KEY missing ya choti hai. Run: /usr/php83/usr/bin/php artisan key:generate && config:cache';
        }

        if (! $request->hasSession()) {
            return 'Session start nahi ho rahi. storage/framework/sessions folder writable (chmod 775) karein.';
        }

        return null;
    }

    private function friendlyMessage(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'domain') || str_contains($message, 'Domain')) {
            return 'AUTH0_DOMAIN galat hai.';
        }

        if (str_contains($message, 'clientId') || str_contains($message, 'clientSecret') || str_contains($message, 'client')) {
            return 'AUTH0_CLIENT_ID / AUTH0_CLIENT_SECRET check karein.';
        }

        if (str_contains($message, 'redirectUri') || str_contains($message, 'redirect')) {
            return 'AUTH0_REDIRECT_URI Auth0 Dashboard callback URL se match karein.';
        }

        if (str_contains($message, 'cookieSecret') || str_contains($message, 'cookie')) {
            return 'APP_KEY issue — php artisan key:generate chalayein.';
        }

        if (str_contains($message, 'stateful') || str_contains($message, 'session') || str_contains($message, 'Session')) {
            return 'Auth0 session issue — storage/framework/sessions writable hona chahiye.';
        }

        return 'Auth0 redirect fail.';
    }

    private function shortError(Throwable $e): string
    {
        $message = preg_replace('/\s+/', ' ', trim($e->getMessage())) ?? '';

        return mb_substr($message, 0, 180);
    }
}
