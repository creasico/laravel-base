<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Events\CredentialTokenCreated;
use Creasi\Base\Events\CredentialTokenDestroyed;
use Creasi\Base\Events\CredentialTokenRefreshed;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;

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
    public function refreshCredentialTokens(Request $request): array
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
            'refresh_token' => $this->getTokenFromRequest($request),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function destroyCredential(Request $request): ?bool
    {
        $accessToken = $this->getCurrentAccessToken($request);

        if ($deleted = $accessToken->delete()) {
            \event(new CredentialTokenDestroyed($this));
        }

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function createAccessToken(): NewAccessToken
    {
        $expiration = (int) (\config('sanctum.expiration') ?: \config('session.lifetime', 120));

        return $this->createToken('access', ['*'], \now()->addMinutes($expiration));
    }

    /**
     * Return the current access token for the user.
     *
     * @return null|\Laravel\Sanctum\PersonalAccessToken
     */
    protected function getCurrentAccessToken(Request $request)
    {
        if (\is_null($tokenString = $this->getTokenFromRequest($request))) {
            return null;
        }

        return $this->tokens()->getRelated()->findToken($tokenString);
    }

    /**
     * Get the token from the request.
     */
    protected function getTokenFromRequest(Request $request): ?string
    {
        if (is_callable(Sanctum::$accessTokenRetrievalCallback)) {
            return (Sanctum::$accessTokenRetrievalCallback)($request);
        }

        return $request->bearerToken();
    }
}
