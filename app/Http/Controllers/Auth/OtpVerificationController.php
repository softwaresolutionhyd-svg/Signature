<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginOtpService;
use App\Services\LoginRateLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    public function __construct(
        private readonly LoginOtpService $loginOtp,
        private readonly LoginRateLimitService $rateLimit
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
        $rateKey = $this->rateLimit->keyForIp('login-otp', $request);

        if ($this->rateLimit->tooManyAttempts($rateKey)) {
            return back()->withErrors([
                'otp' => $this->rateLimit->lockoutMessage($rateKey),
            ]);
        }

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
            $this->rateLimit->hit($rateKey);

            return back()->withErrors([
                'otp' => $this->rateLimit->failedCodeMessage($rateKey, 'OTP'),
            ]);
        }

        $this->rateLimit->clear($rateKey);
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
        $rateKey = $this->rateLimit->keyForIp('login-otp-resend', $request);

        if ($this->rateLimit->tooManyAttempts($rateKey)) {
            return back()->withErrors([
                'otp' => $this->rateLimit->lockoutMessage($rateKey),
            ]);
        }

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

        $this->rateLimit->hit($rateKey);
        $this->loginOtp->markResent($newToken);
        $request->session()->put('login_otp_token', $newToken);

        return back()->with('status', 'Naya OTP bhej diya gaya hai.');
    }
}
