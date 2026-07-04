<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;

final class Auth0UserRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    public function fromAccessToken(array $user): ?Authenticatable
    {
        return $this->resolveLocalUser($user);
    }

    public function fromSession(array $user): ?Authenticatable
    {
        return $this->resolveLocalUser($user);
    }

    private function resolveLocalUser(array $profile): ?Authenticatable
    {
        $sub = trim((string) ($profile['sub'] ?? ''));
        $email = strtolower(trim((string) ($profile['email'] ?? '')));

        if ($sub === '' && $email === '') {
            return null;
        }

        $query = User::query();

        if ($sub !== '') {
            $local = (clone $query)->where('auth0_id', $sub)->first();
            if ($local) {
                return $local;
            }
        }

        if ($email !== '') {
            $local = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
            if ($local) {
                if ($sub !== '' && $local->auth0_id !== $sub) {
                    $local->update(['auth0_id' => $sub]);
                }

                return $local;
            }
        }

        return null;
    }
}
