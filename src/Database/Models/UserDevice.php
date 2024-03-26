<?php

namespace Creasi\Base\Database\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property string $token
 * @property-read \App\Models\User $user
 */
class UserDevice extends EloquentModel
{
    protected $fillable = ['token'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(config('creasi.base.user_model'));
    }
}
