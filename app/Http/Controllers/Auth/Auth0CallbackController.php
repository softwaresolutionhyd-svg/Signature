<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth0LocalUserNotFoundException;
use App\Http\Controllers\Controller;
use Auth0\Laravel\Controllers\CallbackController as SdkCallbackController;
use Auth0\Laravel\Guards\GuardAbstract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Auth0CallbackController extends Controller
{
    public function __invoke(Request $request): Response
    {
        auth()->shouldUse('auth0-session');

        $guard = auth()->guard();
        if (! $guard instanceof GuardAbstract) {
            return redirect()->route('login')->withErrors([
                'login' => 'Auth0 guard configure nahi hai. Admin se contact karein.',
            ]);
        }

        try {
            return app(SdkCallbackController::class)($request);
        } catch (Auth0LocalUserNotFoundException $e) {
            report($e);

            return redirect()->route('login')->withErrors(['login' => $e->getMessage()]);
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors(['login' => $this->friendlyMessage($e)]);
        }
    }

    private function friendlyMessage(Throwable $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'auth0_id') || str_contains($message, 'Unknown column')) {
            return 'Database migration pending hai. Server par yeh chalayein: php artisan migrate --force';
        }

        if (str_contains($message, 'incompatible') || str_contains($message, 'INCOMPATIBLE_GUARD')) {
            return 'Auth0 guard error. Admin se contact karein.';
        }

        return 'Auth0 login fail ho gaya. Email system ke user record se exactly match honi chahiye.';
    }
}
