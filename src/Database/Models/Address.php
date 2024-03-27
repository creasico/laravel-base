<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Factories\AddressFactory;
use Creasi\Base\Enums\AddressType;
use Creasi\Nusa\Models\Address as NusaAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read bool $is_resident
 * @property null|Enums\AddressType $type
 * @property null|string $rt
 * @property null|string $rw
 * @property null|string $summary
 *
 * @method static \Creasi\Base\Database\Factories\AddressFactory<Address> factory()
 */
class Address extends NusaAddress
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['type', 'rt', 'rw', 'summary'];

    protected $casts = [];

    protected static function newFactory()
    {
        return AddressFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Address $address) {
            $address->rt = $address->rt ? \str_pad($address->rt, 3, '0', \STR_PAD_LEFT) : null;
            $address->rw = $address->rw ? \str_pad($address->rw, 3, '0', \STR_PAD_LEFT) : null;
        });
    }

    public function getCasts()
    {
        return \array_merge(parent::getCasts(), [
            'type' => config('creasi.base.address.types', AddressType::class),
        ]);
    }

    public function isResident(): Attribute
    {
        return Attribute::get(fn () => $this->type->isResident());
    }
}
