<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        if (auth0_enabled()) {
            Auth::guard('auth0-session')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/auth0/logout');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
