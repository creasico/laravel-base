<?php

namespace Creasi\Base\Models;

use Creasi\Nusa\Models\Address as NusaAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property-read bool $is_resident
 * @property null|Enums\AddressType $type
 * @property null|string $rt
 * @property null|string $rw
 * @property null|string $summary
 *
 * @method static \Database\Factories\AddressFactory<static> factory()
 */
class Address extends NusaAddress
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'rt',
        'rw',
        'summary',
    ];

    protected $casts = [];

    protected static function newFactory()
    {
        return Factories\AddressFactory::new();
    }

    public function getCasts()
    {
        return \array_merge(parent::getCasts(), [
            'type' => config('creasi.base.address.types', Enums\AddressType::class),
        ]);
    }

    public function isResident(): Attribute
    {
        return Attribute::get(fn () => $this->getAttributeValue('type')->isResident());
    }

    public function rt(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Str::padLeft($value, 3, '0') : null);
    }

    public function rw(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Str::padLeft($value, 3, '0') : null);
    }
}
