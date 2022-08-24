<?php

namespace Creasi\Laravel\Accounts;

use Creasi\Laravel\Factories\RelationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $account_id
 * @property-read Account $account
 * @property-read int $related_id
 * @property-read Account $related
 * @property string $notes
 *
 * @method static RelationFactory<Relation> factory()
 */
class Relation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'account_relations';

    protected $fillable = ['notes'];

    protected static function newFactory()
    {
        return new RelationFactory();
    }

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
    public function related()
    {
        return $this->belongsTo(Account::class, 'related_id');
    }
}
