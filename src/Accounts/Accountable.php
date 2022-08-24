<?php

namespace Creasi\Laravel\Accounts;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property-read int $id
 * @property bool $is_primary
 * @property null|\ArrayObject $payload
 */
class Accountable extends MorphPivot
{
    protected $fillable = ['is_primary', 'payload'];

    protected $casts = [
        'is_primary' => 'boolean',
        'payload' => AsArrayObject::class,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function markAsPrimary(bool $primary = true): bool
    {
        return $this->update(['is_primary' => $primary]);
    }
}
