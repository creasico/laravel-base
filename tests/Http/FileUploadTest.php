<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\FileUpload;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('fileUpload')]
class FileUploadTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(TestCase::class, 'entities')]
    public function should_able_to_retrieve_all_data(string $entity, string $modelClass): void
    {
        Sanctum::actingAs($this->user());
        $model = $modelClass::factory()->withFileUpload()->create();

        $response = $this->getJson("base/{$entity}/{$model->getKey()}/files");

        $response->assertOk();
    }

    #[Test]
    #[DataProviderExternal(TestCase::class, 'entities')]
    public function should_able_to_store_new_data(string $entity, string $modelClass): void
    {
        Storage::fake();
        Sanctum::actingAs($this->user());

        $model = $modelClass::factory()->withFileUpload()->create();
        $data = FileUpload::factory()->withoutFile()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');

        $response = $this->postJson("base/{$entity}/{$model->getKey()}/files", $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = FileUpload::factory()->createOne();

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
        Sanctum::actingAs($this->user());
        $model = FileUpload::factory()->createOne();

        $response = $this->deleteJson("base/files/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
