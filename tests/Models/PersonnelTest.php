<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\File;
use Creasi\Base\Models\Enums\PersonnelRelativeStatus;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('personnel')]
class PersonnelTest extends TestCase
{
    #[Test]
    public function should_be_exists()
    {
        $model = Personnel::factory()->createOne();

        $this->assertModelExists($model);
    }

    #[Test]
    public function should_have_addresses()
    {
        $person = Personnel::factory()->withAddress()->createOne();

        $this->assertCount(1, $person->addresses);
        $this->assertInstanceOf(Address::class, $person->addresses->first());
    }

    #[Test]
    public function should_have_documents()
    {
        $company = Personnel::factory()->createOne();
        $document = File::factory()->createOne();

        $company->files()->save($document);

        $this->assertCount(1, $company->files);
        $this->assertCount(1, $document->ownedByPersonnels);
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
