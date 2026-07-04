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

            $detail = config('app.debug') ? ' ('.$e->getMessage().')' : '';

            return redirect()->route('login')->withErrors([
                'login' => $this->friendlyMessage($e).$detail,
            ]);
        }
    }

    private function friendlyMessage(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'domain') || str_contains($message, 'Domain')) {
            return 'AUTH0_DOMAIN galat hai. Sirf hostname likhein: signature-ss.us.auth0.com (bina https://).';
        }

        if (str_contains($message, 'client') || str_contains($message, 'Client')) {
            return 'AUTH0_CLIENT_ID ya AUTH0_CLIENT_SECRET galat/missing hai. .env check karein, phir: php artisan config:cache';
        }

        if (str_contains($message, 'redirect') || str_contains($message, 'Redirect')) {
            return 'AUTH0_REDIRECT_URI galat hai. Auth0 Dashboard mein Allowed Callback URL: https://signature.softwaresolutions.pk/callback';
        }

        if (str_contains($message, 'cookie') || str_contains($message, 'Cookie')) {
            return 'APP_KEY ya AUTH0_COOKIE_SECRET issue. .env mein APP_KEY set hona chahiye.';
        }

        return 'Auth0 redirect fail. Server par yeh check karein: grep AUTH0 .env && php artisan config:cache';
    }
}
