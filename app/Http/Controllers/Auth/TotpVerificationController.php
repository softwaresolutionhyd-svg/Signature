<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginTotpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotpVerificationController extends Controller
{
    public function __construct(
        private readonly LoginTotpService $loginTotp
    ) {
        $this->middleware('guest');
    }

    public function show(Request $request)
    {
        if (! $request->session()->has('login_totp_token')) {
            return redirect()->route('login')->withErrors([
                'login' => '2FA session expire ho gayi. Dobara login karein.',
            ]);
        }

        return view('auth.verify-totp');
    }

    public function verify(Request $request)
    {
        $token = (string) $request->session()->get('login_totp_token', '');
        if ($token === '') {
            return redirect()->route('login')->withErrors([
                'login' => '2FA session expire ho gayi. Dobara login karein.',
            ]);
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ]);

        $result = $this->loginTotp->verify($token, $data['code']);
        if ($result === null) {
            return back()->withErrors([
                'code' => 'Galat ya expire code. Google Authenticator se 6-digit code enter karein.',
            ]);
        }

        $request->session()->forget('login_totp_token');
        Auth::login($result['user'], $result['remember']);
        $request->session()->regenerate();

        if ($result['user']->isPlatformSuperAdmin()) {
            $request->session()->forget('active_company_id');
        }

        if ($result['user']->must_change_password ?? false) {
            session()->flash('warning', 'Security: pehle naya password set karein.');
        }

        return redirect()->intended('/dashboard');
    }
}
