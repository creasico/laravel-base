<?php

namespace Creasi\Tests\Models;

use Carbon\CarbonImmutable;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Database\Models\Profile;
use Creasi\Base\Enums\Education;
use Creasi\Base\Enums\Religion;
use Creasi\Nusa\Models\Regency;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('profile')]
class ProfileTest extends TestCase
{
    #[Test]
    public function should_have_correct_attributes_cast()
    {
        $model = Profile::factory()->createOne();

        $this->assertModelExists($model);
        $this->assertInstanceOf(CarbonImmutable::class, $model->birth_date);
        $this->assertInstanceOf(Education::class, $model->education);
        $this->assertInstanceOf(Religion::class, $model->religion);
    }

    #[Test]
    public function should_owned_by_personnel()
    {
        $person = Personnel::factory()->createOne();
        $profile = Profile::factory()->createOne();

        $profile->identity()->associate($person)->save();

        $this->assertModelExists($person->profile);
        $this->assertInstanceOf(Regency::class, $profile->birthPlace);
    }
}
