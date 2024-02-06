<?php

namespace Creasi\Base\Models\Concerns;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Creasi\Base\Models\User
     */
    public function user()
    {
        return $this->belongsTo(app('creasi.base.user_model'));
    }
}
