<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Model;
use Creasi\Base\Database\Models\Concerns\WithTaxInfo;
use Creasi\Base\Database\Models\Contracts\HasTaxInfo;
use Creasi\Base\Enums\Education;
use Creasi\Base\Enums\Religion;
use Creasi\Nusa\Models\Concerns\WithRegency;
use Creasi\Nusa\Models\Regency;

/**
 * @property null|string $nik
 * @property null|string $prefix
 * @property null|string $suffix
 * @property null|\Carbon\CarbonImmutable $birth_date
 * @property null|int $birth_place_code
 * @property null|Education $education
 * @property null|Religion $religion
 * @property null|string $summary
 * @property-read null|Regency $birthPlace
 *
 * @method static \Creasi\Base\Database\Factories\ProfileFactory<Profile> factory()
 */
class Profile extends Model implements HasTaxInfo
{
    use WithRegency {
        regency as birthPlace;
    }
    use WithTaxInfo;

    protected $fillable = [
        'nik',
        'prefix',
        'suffix',
        'birth_date',
        'birth_place_code',
        'education',
        'religion',
    ];

    protected $casts = [
        'birth_date' => 'immutable_date',
        'birth_place_code' => 'int',
        'education' => Education::class,
        'religion' => Religion::class,
    ];

    protected $regencyKey = 'birth_place_code';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|Personnel
     */
    public function identity()
    {
        return $this->morphTo('identity');
    }
}
