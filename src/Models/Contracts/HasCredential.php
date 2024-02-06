<?php

namespace Creasi\Base\Models\Contracts;

/**
 * @property null|int|string $user_id
 * @property-read null|\Illuminate\Foundation\Auth\User $user
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasCredential
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Illuminate\Foundation\Auth\User
     */
    public function user();
}
