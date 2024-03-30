<?php

namespace Creasi\Tests\Unit\Models;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\Organization;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\FileType;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('fileUpload')]
class FileUploadTest extends TestCase
{
    #[Test]
    public function should_have_revisions()
    {
        $file = UploadedFile::fake()->create('original.pdf');

        $original = File::store($file, 'document');

        $revision = $original->createRevision(
            UploadedFile::fake()->create('revision.pdf')
        );

        $this->assertTrue($original->is($revision->revisionOf));
        $this->assertCount(1, $original->revisions);
        $this->assertNotSame($original, $revision);
        $this->assertSame($original->name, $revision->name);
    }

    #[Test]
    public function could_attached_to_personnel()
    {
        $person = Person::factory()->createOne();
        $files = [
            'first' => UploadedFile::fake()->create('first.pdf'),
            'second' => UploadedFile::fake()->create('second.pdf'),
        ];

        foreach ($files as $name => $file) {
            $person->storeFile(FileType::Document, $file, $name);
        }

        $this->assertCount(2, $person->files);

        foreach ($person->files as $file) {
            $this->assertCount($file->attaches()->count(), $file->ownedByPersons);
        }
    }

    #[Test]
    public function could_attached_to_many_people()
    {
        $people = Person::factory(2)->create();
        $file = UploadedFile::fake()->create('document.pdf');

        foreach ($people as $person) {
            $person->storeFile(FileType::Document, $file, 'document');

            $this->assertCount(1, $person->files);
        }
    }

    #[Test]
    public function could_attached_to_company()
    {
        $company = Organization::factory()->createOne();
        $files = [
            'first' => UploadedFile::fake()->create('first.pdf'),
            'second' => UploadedFile::fake()->create('second.pdf'),
        ];

        foreach ($files as $name => $file) {
            $company->storeFile(FileType::Document, $file, $name);
        }

        $this->assertCount(2, $company->files);

        foreach ($company->files as $file) {
            $this->assertCount($file->attaches()->count(), $file->ownedByCompanies);
        }
    }

    #[Test]
    public function could_attached_to_many_companies()
    {
        $companies = Organization::factory(2)->create();
        $file = UploadedFile::fake()->create('document.pdf');

        foreach ($companies as $company) {
            $company->storeFile(FileType::Document, $file, 'document');

            $this->assertCount(1, $company->files);
        }
    }
}
