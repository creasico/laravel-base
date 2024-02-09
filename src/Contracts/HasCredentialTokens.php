<?php

namespace Creasi\Base\Contracts;

use Illuminate\Http\Request;
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
    public function refreshCredentialTokens(Request $request): array;

    /**
     * Destroy existing Credential Token.
     */
    public function destroyCredential(Request $request): ?bool;

    /**
     * Create new Access Token.
     */
    public function createAccessToken(): NewAccessToken;
}
