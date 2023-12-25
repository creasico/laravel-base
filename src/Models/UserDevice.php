<?php

namespace Creasi\Base\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $token
 * @property-read \App\Models\User $user
 */
class UserDevice extends EloquentModel
{
    protected $fillable = [
        'token',
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|Personnel
     */
    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
