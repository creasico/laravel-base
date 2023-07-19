<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\User;

/**
 * @mixin \Creasi\Base\Contracts\HasCredential
 */
trait WithCredential
{
    /**
     * Initialize the trait.
     */
    public function initializeWithCredential(): void
    {
        $this->mergeCasts([
            'user_id' => 'int',
        ]);

        $this->mergeFillable([
            'user_id',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
