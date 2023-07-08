<?php

namespace Creasi\Base\Models;

use Creasi\Nusa\Models\Address as NusaAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property bool $is_resident
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
        'is_resident',
        'rt',
        'rw',
        'summary',
    ];

    protected $casts = [
        'is_resident' => 'bool',
    ];

    public function rt(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Str::padLeft($value, 3, '0') : null);
    }

    public function rw(): Attribute
    {
        return Attribute::set(fn ($value) => $value ? Str::padLeft($value, 3, '0') : null);
    }
}
