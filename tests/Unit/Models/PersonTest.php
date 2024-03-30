<?php

namespace Creasi\Tests\Unit\Models;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonRelativeStatus;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('personnel')]
class PersonTest extends TestCase
{
    #[Test]
    public function should_have_addresses()
    {
        /** @var Person */
        $person = Person::factory()->withAddress()->createOne();

        $this->assertCount(1, $person->addresses);
        $this->assertInstanceOf(Gender::class, $person->gender);
        $this->assertInstanceOf(Address::class, $person->addresses->first());
    }

    #[Test]
    public function should_have_avatar_image()
    {
        /** @var Person */
        $person = Person::factory()->createOne();

        $avatar = $person->setAvatar(
            UploadedFile::fake()->image('avatar.png')
        );

        $this->assertInstanceOf(File::class, $person->avatar);
        $this->assertTrue($person->avatar->is_internal);
    }

    #[Test]
    public function should_have_relatives()
    {
        /** @var Person */
        $person = Person::factory()->createOne();
        /** @var Person */
        $relative = Person::factory()->createOne();

        $person->addRelative($relative, PersonRelativeStatus::Parent);

        $this->assertEquals($relative->getKey(), $person->relatives->first()->getKey());
    }
}
