<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Company;
use Creasi\Base\Models\FileAttached;
use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        $original = FileUpload::factory()->createOne([
            'path' => 'path/to/file.pdf',
        ]);

        $revision = $original->addRevision('path/to/revision.pdf');

        $this->assertTrue($original->is($revision->revisionOf));
        $this->assertCount(1, $original->revisions);
        $this->assertNotSame($original, $revision);
        $this->assertSame($original->name, $revision->name);
    }

    #[Test]
    public function could_attached_to_personnel()
    {
        $person = Personnel::factory()->createOne();
        $files = FileUpload::factory(2)->sequence(fn (Sequence $seq) => [
            'path' => "file-{$seq->index}.pdf"
        ])->create();

        $person->files()->saveMany($files);

        $this->assertCount(2, $person->files);

        foreach ($person->files as $i => $file) {
            $this->assertTrue($file->is($files[$i]));

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
            $person->files()->sync($file);

            $this->assertCount(1, $person->files);
        }

        $this->assertCount($file->attaches()->count(), $file->ownedByPersonnels);

        $revision = $file->addRevision('path/to/revision.pdf');

        $this->assertCount($revision->attaches()->count(), $revision->ownedByPersonnels);
    }

    #[Test]
    public function could_attached_to_company()
    {
        $company = Company::factory()->createOne();
        $files = FileUpload::factory(2)->sequence(fn (Sequence $seq) => [
            'path' => "file-{$seq->index}.pdf"
        ])->create();

        $company->files()->saveMany($files);

        $this->assertCount(2, $company->files);

        foreach ($company->files as $i => $file) {
            $this->assertTrue($file->is($files[$i]));

            $this->assertCount($file->attaches()->count(), $file->ownedByCompanies);
        }
    }

    #[Test]
    public function could_attached_to_many_companies()
    {
        $companies = Company::factory(2)->create();
        $file = FileUpload::factory()->createOne([
            'path' => 'path/to/file.pdf',
        ]);

        foreach ($companies as $company) {
            $company->files()->sync($file);

            $this->assertCount(1, $company->files);
        }

        $this->assertCount($file->attaches()->count(), $file->ownedByCompanies);
    }
}
