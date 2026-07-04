<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    public function __construct(
        private readonly LoginOtpService $loginOtp
    ) {
        $this->middleware('guest');
    }

    public function show(Request $request)
    {
        if (! $request->session()->has('login_otp_token')) {
            return redirect()->route('login')->withErrors([
                'login' => 'OTP session expire ho gayi. Dobara login karein.',
            ]);
        }

        return view('auth.verify-otp', [
            'maskedPhone' => session('otp_phone_masked', '****'),
        ]);
    }

    public function verify(Request $request)
    {
        $token = (string) $request->session()->get('login_otp_token', '');
        if ($token === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'OTP session expire ho gayi. Dobara login karein.',
            ]);
        }

        $data = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $result = $this->loginOtp->verify($token, $data['otp']);
        if ($result === null) {
            return back()->withErrors([
                'otp' => 'Galat ya expire OTP. Dobara try karein.',
            ]);
        }

        $request->session()->forget(['login_otp_token', 'otp_phone_masked']);
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

    public function resend(Request $request)
    {
        $token = (string) $request->session()->get('login_otp_token', '');
        if ($token === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'OTP session expire ho gayi. Dobara login karein.',
            ]);
        }

        if (! $this->loginOtp->canResend($token)) {
            return back()->withErrors([
                'otp' => '1 minute baad dubara OTP bhej sakte hain.',
            ]);
        }

        try {
            $newToken = $this->loginOtp->regenerateAndSend($token);
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors([
                'otp' => 'OTP dubara bhejne mein masla aaya. Settings check karein.',
            ]);
        }

        if ($newToken === null) {
            return redirect()->route('login')->withErrors([
                'login' => 'OTP session expire ho gayi. Dobara login karein.',
            ]);
        }

        $this->loginOtp->markResent($newToken);
        $request->session()->put('login_otp_token', $newToken);

        return back()->with('status', 'Naya OTP bhej diya gaya hai.');
    }
}
