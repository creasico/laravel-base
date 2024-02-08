<?php

namespace Creasi\Base\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
