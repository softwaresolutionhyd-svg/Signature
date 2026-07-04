<?php

namespace App\Http\Controllers;

use App\Services\TotpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    public function __construct(
        private readonly TotpService $totp
    ) {}

    public function setup(Request $request)
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit')->with('status', 'Google Authenticator pehle se enabled hai.');
        }

        $secret = $this->totp->generateSecret();
        $request->session()->put('two_factor_pending_secret', $secret);

        return view('profile.two-factor-setup', [
            'secret' => $secret,
            'qrCodeSvg' => $this->totp->getQrCodeSvg($user, $secret),
        ]);
    }

    public function confirm(Request $request)
    {
        $secret = (string) $request->session()->get('two_factor_pending_secret', '');
        if ($secret === '') {
            return redirect()->route('profile.edit')->withErrors([
                'two_factor' => 'Setup session expire ho gayi. Dobara try karein.',
            ]);
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if (! $this->totp->verifyKey($secret, $data['code'])) {
            return back()->withErrors([
                'code' => 'Galat code. Google Authenticator se sahi 6-digit code enter karein.',
            ]);
        }

        $user = $request->user();
        $recoveryCodes = $this->totp->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => $recoveryCodes,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('two_factor_pending_secret');

        return view('profile.two-factor-recovery', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    public function disable(Request $request)
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit');
        }

        $data = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'code' => ['required', 'string', 'max:20'],
        ]);

        $codeValid = $this->totp->verifyForUser($user, $data['code'])
            || $this->totp->verifyRecoveryCode($user, $data['code']);

        if (! $codeValid) {
            return back()->withErrors([
                'code' => 'Password ya Authenticator code galat hai.',
            ]);
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('profile.edit')->with('status', 'Google Authenticator 2FA disable ho gaya.');
    }
}
