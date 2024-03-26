<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\FileUpload;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\FileUploadType;
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

        $original = FileUpload::store($file, 'document');

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
        $person = Personnel::factory()->createOne();
        $files = [
            'first' => UploadedFile::fake()->create('first.pdf'),
            'second' => UploadedFile::fake()->create('second.pdf'),
        ];

        foreach ($files as $name => $file) {
            $person->storeFile(FileUploadType::Document, $file, $name);
        }

        $this->assertCount(2, $person->files);

        foreach ($person->files as $file) {
            $this->assertCount($file->attaches()->count(), $file->ownedByPersonnels);
        }
    }

    #[Test]
    public function could_attached_to_many_personnels()
    {
        $people = Personnel::factory(2)->create();
        $file = UploadedFile::fake()->create('document.pdf');

        foreach ($people as $person) {
            $person->storeFile(FileUploadType::Document, $file, 'document');

            $this->assertCount(1, $person->files);
        }
    }

    #[Test]
    public function could_attached_to_company()
    {
        $company = Business::factory()->createOne();
        $files = [
            'first' => UploadedFile::fake()->create('first.pdf'),
            'second' => UploadedFile::fake()->create('second.pdf'),
        ];

        foreach ($files as $name => $file) {
            $company->storeFile(FileUploadType::Document, $file, $name);
        }

        $this->assertCount(2, $company->files);

        foreach ($company->files as $file) {
            $this->assertCount($file->attaches()->count(), $file->ownedByCompanies);
        }
    }

    #[Test]
    public function could_attached_to_many_companies()
    {
        $companies = Business::factory(2)->create();
        $file = UploadedFile::fake()->create('document.pdf');

        foreach ($companies as $company) {
            $company->storeFile(FileUploadType::Document, $file, 'document');

            $this->assertCount(1, $company->files);
        }
    }
}
