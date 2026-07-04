<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\Auth0LocalUserNotFoundException;
use App\Models\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Schema;

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

        $hasAuth0IdColumn = Schema::hasColumn('users', 'auth0_id');

        if ($hasAuth0IdColumn && $sub !== '') {
            $local = User::query()->where('auth0_id', $sub)->first();
            if ($local) {
                return $local;
            }
        }

        if ($email !== '') {
            $local = User::query()->whereRaw('LOWER(email) = ?', [$email])->first();
            if ($local) {
                if ($hasAuth0IdColumn && $sub !== '' && $local->auth0_id !== $sub) {
                    $local->update(['auth0_id' => $sub]);
                }

                return $local;
            }

            throw new Auth0LocalUserNotFoundException($email);
        }

        return null;
    }
}
