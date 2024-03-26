<?php

namespace Creasi\Base\Database\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Creasi\Base\Database\Models\Contracts\HasCredential
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

        $this->mergeFillable(['user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('creasi.base.user_model'));
    }
}
