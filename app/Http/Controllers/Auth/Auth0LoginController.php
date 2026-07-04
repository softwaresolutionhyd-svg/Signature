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

            return redirect()->route('login')->withErrors([
                'login' => $this->friendlyMessage($e),
            ]);
        }
    }

    private function friendlyMessage(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'domain') || str_contains($message, 'Domain')) {
            return 'AUTH0_DOMAIN galat hai. Sirf hostname likhein, bina https:// (e.g. tenant.us.auth0.com).';
        }

        if (str_contains($message, 'client') || str_contains($message, 'Client')) {
            return 'AUTH0_CLIENT_ID ya AUTH0_CLIENT_SECRET galat hai. .env check karein.';
        }

        return 'Auth0 redirect fail ho gaya. Settings check karein: AUTH0_DOMAIN, CLIENT_ID, REDIRECT_URI.';
    }
}
