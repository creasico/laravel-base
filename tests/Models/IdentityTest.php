<?php

namespace Creasi\Tests\Models;

use Carbon\CarbonImmutable;
use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Enums\Religion;
use Creasi\Base\Models\Identity;
use Creasi\Base\Models\Personnel;
use Creasi\Nusa\Models\Regency;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('identity')]
class IdentityTest extends TestCase
{
    #[Test]
    public function should_be_exists()
    {
        $model = Identity::factory()->createOne();

        $this->assertModelExists($model);
        $this->assertInstanceOf(CarbonImmutable::class, $model->birth_date);
        $this->assertInstanceOf(Education::class, $model->education);
        $this->assertInstanceOf(Gender::class, $model->gender);
        $this->assertInstanceOf(Religion::class, $model->religion);
    }

    #[Test]
    public function should_owned_by_personnel()
    {
        $person = Personnel::factory()->createOne();
        $identity = Identity::factory()->createOne();

        $identity->profile()->associate($person)->save();

        $this->assertModelExists($person->identity);
        $this->assertInstanceOf(Regency::class, $identity->birthPlace);
    }
}
