<?php

namespace Creasi\Base\Events;

use Creasi\Base\Contracts\HasCredentialTokens;

class CredentialTokenDestroyed
{
    public function __construct(
        public readonly HasCredentialTokens $user
    ) {
        // .
    }
}
