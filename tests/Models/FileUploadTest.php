<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Company;
use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('files')]
class FileUploadTest extends TestCase
{
    #[Test]
    public function should_be_exists()
    {
        $model = FileUpload::factory()->createOne();

        $this->assertModelExists($model);

        return $model;
    }

    #[Test]
    public function should_have_revisions()
    {
        $file = UploadedFile::fake()->create('original.pdf');

        $original = FileUpload::store(FileUploadType::Document, $file, 'document');

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
        $file = FileUpload::factory()->createOne([
            'path' => 'path/to/file.pdf',
        ]);

        foreach ($people as $person) {
            $person->files()->attach($file);

            $this->assertCount(1, $person->files);
        }

        $this->assertCount($file->attaches()->count(), $file->ownedByPersonnels);

        $revision = $file->createRevision('path/to/revision.pdf');

        $this->assertCount($revision->attaches()->count(), $revision->ownedByPersonnels);
    }

    #[Test]
    public function could_attached_to_company()
    {
        $company = Company::factory()->createOne();
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
        $companies = Company::factory(2)->create();
        $file = UploadedFile::fake()->create('document.pdf');

        foreach ($companies as $company) {
            $company->storeFile(FileUploadType::Document, $file, 'document');

            $this->assertCount(1, $company->files);
        }
    }
}
