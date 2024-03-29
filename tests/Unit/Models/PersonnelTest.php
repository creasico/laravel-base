<?php

namespace Creasi\Tests\Unit\Models;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonnelRelativeStatus;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('personnel')]
class PersonnelTest extends TestCase
{
    #[Test]
    public function should_have_addresses()
    {
        /** @var Personnel */
        $person = Personnel::factory()->withAddress()->createOne();

        $this->assertCount(1, $person->addresses);
        $this->assertInstanceOf(Gender::class, $person->gender);
        $this->assertInstanceOf(Address::class, $person->addresses->first());
    }

    #[Test]
    public function should_have_avatar_image()
    {
        /** @var Personnel */
        $person = Personnel::factory()->createOne();

        $avatar = $person->setAvatar(
            UploadedFile::fake()->image('avatar.png')
        );

        $this->assertInstanceOf(File::class, $person->avatar);
        $this->assertTrue($person->avatar->is_internal);
    }

    #[Test]
    public function should_have_relatives()
    {
        /** @var Personnel */
        $person = Personnel::factory()->createOne();
        /** @var Personnel */
        $relative = Personnel::factory()->createOne();

        $person->addRelative($relative, PersonnelRelativeStatus::Parent);

        $this->assertEquals($relative->getKey(), $person->relatives->first()->getKey());
    }
}
