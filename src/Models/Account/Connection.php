<?php

namespace Creasi\Laravel\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read int $id
 * @property-read int $account_id
 * @property-read Account $account
 * @property-read int $connected_id
 * @property-read Account $related
 * @property string $notes
 */
class Connection extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'account_connections';

    protected $fillable = ['notes'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Account
     */
    public function connected()
    {
        return $this->belongsTo(Account::class, 'connected_id');
    }
}
