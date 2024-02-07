<?php

namespace Creasi\Base\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property null|int|string $user_id
 * @property-read null|\Illuminate\Foundation\Auth\User $user
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasCredential
{
    /**
     * @return BelongsTo|\Illuminate\Foundation\Auth\User
     */
    public function user(): BelongsTo;
}
