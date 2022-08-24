<?php

namespace Creasi\Laravel\Accounts;

use Creasi\Laravel\Factories\FieldFactory;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read int $account_id
 * @property-read Account $account
 * @property-read string $label
 * @property null|Field\Type $type
 * @property-read string $key
 * @property-read string $cast
 * @property null|\ArrayObject $payload
 * @property-read \Creasi\Laravel\Accounts\Accountable $defined
 *
 * @method static FieldFactory<Field> factory()
 */
class Field extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'account_fields';

    protected $fillable = ['type', 'key', 'cast', 'payload'];

    protected $casts = [
        'type' => Field\Type::class,
        'cast' => Field\Cast::class,
        'payload' => Field\CustomObject::class,
    ];

    protected static function newFactory()
    {
        return new FieldFactory();
    }

    public function getLabelAttribute()
    {
        return \trans('contacts.'.$this->attributes['key'].'.label');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function markAsPrimary(bool $primary = true)
    {
        return $this->update(['is_primary' => $primary]);
    }
}
