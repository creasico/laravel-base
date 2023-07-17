<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\Identity as IdentityContract;
use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Enums\Religion;
use Creasi\Nusa\Models\Regency;

/**
 * @property null|int $user_id
 * @property null|string $nik
 * @property null|string $prefix
 * @property string $fullname
 * @property null|string $suffix
 * @property null|\Carbon\CarbonImmutable $birth_date
 * @property null|int $birth_place_code
 * @property null|Education $education
 * @property null|Gender $gender
 * @property null|Religion $religion
 * @property null|string $photo_path
 * @property null|string $summary
 * @property-read null|User $user
 * @property-read null|Regency $birthPlace
 *
 * @method static \Database\Factories\IdentityFactory<static> factory()
 */
class Identity extends Model implements IdentityContract
{
    protected $fillable = [
        'user_id',
        'nik',
        'prefix',
        'fullname',
        'suffix',
        'birth_date',
        'birth_place_code',
        'education',
        'gender',
        'religion',
        'photo_path',
        'summary',
    ];

    protected $casts = [
        'user_id' => 'int',
        'birth_date' => 'immutable_date',
        'birth_place_code' => 'int',
        'education' => Education::class,
        'gender' => Gender::class,
        'religion' => Religion::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function profile()
    {
        return $this->morphTo('identity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Regency
     */
    public function birthPlace()
    {
        return $this->belongsTo(Regency::class, 'birth_place_code');
    }
}
