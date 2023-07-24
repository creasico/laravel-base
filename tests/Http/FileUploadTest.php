<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('fileUpload')]
class FileUploadTest extends TestCase
{
    #[Test]
    public function should_receive_404_when_no_data_available(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/files');

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson('base/files');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());

        $data = FileUpload::factory()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');
        $data['type'] = FileUploadType::Document->value;

        $response = $this->postJson('base/files', $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson("base/files/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = FileUpload::factory()->createOne();

        $response = $this->putJson("base/files/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->deleteJson("base/files/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
