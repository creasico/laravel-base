<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Events\CredentialTokenCreated;
use Creasi\Base\Events\CredentialTokenDestroyed;
use Creasi\Base\Events\CredentialTokenRefreshed;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @mixin \Creasi\Base\Contracts\HasCredential
 */
trait WithCredentialTokens
{
    use HasApiTokens;

    /**
     * Initialize the trait.
     */
    public function initializeWithCredentialTokens(): void
    {
        // .
    }

    /**
     * {@inheritdoc}
     */
    public function createCredentialTokens(): array
    {
        $access = $this->createAccessToken();
        $refresh = $this->createToken('refresh', ['refresh-auth'], \now()->addDays(7));

        \event(new CredentialTokenCreated($this));

        return [
            'access_token' => $access->plainTextToken,
            'expires_at' => $access->accessToken->expires_at,
            'refresh_token' => $refresh->plainTextToken,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function refreshCredentialTokens(string $refreshToken): array
    {
        $this->tokens()
            ->where('name', 'access')
            ->where('expires_at', '<', now())
            ->delete();

        $access = $this->createAccessToken();

        \event(new CredentialTokenRefreshed($this));

        return [
            'access_token' => $access->plainTextToken,
            'expires_at' => $access->accessToken->expires_at,
            'refresh_token' => $refreshToken,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function destroyCredential(string $accessToken): ?bool
    {
        $token = PersonalAccessToken::findToken($accessToken);

        if ($deleted = $token->delete()) {
            \event(new CredentialTokenDestroyed($this));
        }

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function createAccessToken(): NewAccessToken
    {
        $expiration = \config('sanctum.expiration');

        return $this->createToken('access', ['*'], $expiration ? \now()->addMinutes($expiration) : null);
    }
}
