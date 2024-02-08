<?php

namespace Creasi\Base\Contracts;

use Laravel\Sanctum\Contracts\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

/**
 * @mixin \Illuminate\Foundation\Auth\User
 */
interface HasCredentialTokens extends HasApiTokens
{
    /**
     * Retreive the Credential Tokens.
     */
    public function createCredentialTokens(): array;

    /**
     * Refresh the Credential Token.
     */
    public function refreshCredentialTokens(string $refreshToken): array;

    /**
     * Destroy existing Credential Token.
     */
    public function destroyCredential(string $accessToken): ?bool;

    /**
     * Create new Access Token.
     */
    public function createAccessToken(): NewAccessToken;
}
