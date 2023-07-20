<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Enums\PersonnelRelativeStatus;
use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\Personnel;
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
        $person = Personnel::factory()->withAddress()->createOne();

        $this->assertCount(1, $person->addresses);
        $this->assertInstanceOf(Gender::class, $person->gender);
        $this->assertInstanceOf(Address::class, $person->addresses->first());
    }

    #[Test]
    public function should_have_avatar_image()
    {
        $person = Personnel::factory()->createOne();

        $avatar = $person->setAvatar(
            UploadedFile::fake()->image('avatar.png')
        );

        $this->assertInstanceOf(FileUpload::class, $person->avatar);
        $this->assertEquals(FileUploadType::Avatar, $person->avatar->type);
        $this->assertTrue($person->avatar->is_internal);
    }

    #[Test]
    public function should_have_relatives()
    {
        $person = Personnel::factory()->createOne();
        $relative = Personnel::factory()->createOne();

        $person->addRelative($relative, PersonnelRelativeStatus::Parent);

        $this->assertEquals($relative->getKey(), $person->relatives->first()->getKey());
    }
}
