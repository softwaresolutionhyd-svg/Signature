<?php

namespace App\Http\Controllers;

use App\Support\AppPasswordRules;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'mustChangePassword' => (bool) ($request->user()->must_change_password ?? false),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $mustChange = (bool) ($user->must_change_password ?? false);

        $rules = [
            'name' => ['required', 'string', 'max:150'],
        ];

        if ($mustChange) {
            $rules['password'] = AppPasswordRules::requiredConfirmed();
        } elseif ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password:web'];
            $rules['password'] = AppPasswordRules::requiredConfirmed();
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];

        if (! empty($data['password'] ?? null)) {
            $user->password = $data['password'];
            $user->must_change_password = false;
        }

        $user->save();

        $message = $mustChange
            ? 'Naya password set ho gaya. Ab aap software use kar sakte hain.'
            : 'Profile updated.';

        return redirect()->route('profile.edit')->with('status', $message);
    }
}
