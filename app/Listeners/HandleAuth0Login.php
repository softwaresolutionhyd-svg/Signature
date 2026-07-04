<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;

class HandleAuth0Login
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        if (! $user instanceof User) {
            return;
        }

        if ($user->isPlatformSuperAdmin()) {
            session()->forget('active_company_id');
        }

        if ($user->must_change_password ?? false) {
            session()->flash('warning', 'Security: pehle naya password set karein.');
        }
    }
}
