<?php

namespace Creasi\Laravel\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property-read int $id
 * @property-read int $account_id
 */
class Accountable extends MorphPivot
{
    protected $fillable = ['account_id'];

    protected $casts = [
        'is_primary' => 'boolean',
        'payload' => AsArrayObject::class,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
