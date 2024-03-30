<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Enums\FileType;
use Creasi\Tests\Feature\TestCase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('files')]
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

        $user->profile->storeFile(FileType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());

        $data = File::factory()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');
        $data['type'] = FileType::Document->value;

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->profile->storeFile(FileType::Document, '/doc/file.pdf', 'document');

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = File::factory()->createOne();

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->profile->storeFile(FileType::Document, '/doc/file.pdf', 'document');

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();
    }

    #[Test]
    public function should_able_to_restore_deleted_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = $user->profile->storeFile(FileType::Document, '/doc/file.pdf', 'document');

        $model->delete();

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk();
    }
}
