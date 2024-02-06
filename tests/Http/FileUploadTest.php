<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Enums\FileUploadType;
use Creasi\Base\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('fileUpload')]
class FileUploadTest extends TestCase
{
    protected string $apiPath = 'files';

    #[Test]
    public function should_receive_404_when_no_data_available(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());

        $data = FileUpload::factory()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');
        $data['type'] = FileUploadType::Document->value;

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = FileUpload::factory()->createOne();

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->identity->storeFile(FileUploadType::Document, '/doc/file.pdf', 'document');

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();
    }
}
