<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class Auth0LocalUserNotFoundException extends RuntimeException
{
    public function __construct(string $email)
    {
        parent::__construct(
            "Auth0 email ({$email}) system mein registered nahi hai. Pehle Employees/Users mein same email se user banao."
        );
    }
}
