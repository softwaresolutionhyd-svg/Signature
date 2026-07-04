<?php

declare(strict_types=1);

namespace App\Listeners;

use Auth0\Laravel\Events\AuthenticationFailed;

class HandleAuth0AuthenticationFailed
{
    public function handle(AuthenticationFailed $event): void
    {
        $event->throwException = false;
    }
}
