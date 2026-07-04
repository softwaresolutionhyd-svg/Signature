<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    /** Max failed login attempts before lockout. */
    protected $maxAttempts = 5;

    /** Lockout duration in minutes. */
    protected $decayMinutes = 10;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * @param  \App\Models\User  $user
     */
    protected function authenticated(Request $request, $user): void
    {
        if ($user->isPlatformSuperAdmin()) {
            $request->session()->forget('active_company_id');
        }

        if ($user->must_change_password ?? false) {
            session()->flash('warning', 'Security: pehle naya password set karein.');
        }
    }

    /**
     * After logout go straight to login (no marketing / welcome page).
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }

    /**
     * Login field name used by throttling / trait.
     */
    public function username(): string
    {
        return 'login';
    }

    /**
     * Validate simple username + password.
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            'login' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Resolve username to stored email (supports plain username).
     *
     * Users can enter:
     * - full email, or
     * - username part before @ (e.g. superadmin)
     */
    protected function credentials(Request $request): array
    {
        $login = trim((string) $request->input('login', ''));
        $password = (string) $request->input('password', '');

        if ($login === '') {
            return ['email' => '', 'password' => $password];
        }

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $login, 'password' => $password];
        }

        $user = User::query()
            ->whereRaw("LOWER(SUBSTRING_INDEX(email, '@', 1)) = ?", [mb_strtolower($login)])
            ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($login)])
            ->first();

        if (! $user) {
            return ['email' => '__invalid__', 'password' => $password];
        }

        return ['email' => (string) $user->email, 'password' => $password];
    }
}
