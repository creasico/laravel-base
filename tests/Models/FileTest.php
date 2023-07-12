<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\File;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('document')]
class FileTest extends TestCase
{
    #[Test]
    public function should_be_exists()
    {
        $model = File::factory()->createOne();

        $this->assertModelExists($model);

        return $model;
    }

    #[Test]
    public function should_have_revisions()
    {

        $original = File::factory()->createOne([
            'path' => 'some/place/elsewhere.pdf',
        ]);

        $revision = $original->addRevision('other/place/elsewhere.pdf');

        $this->assertTrue($original->is($revision->revisionOf));
        $this->assertCount(1, $original->revisions);
        $this->assertNotSame($original, $revision);
        $this->assertSame($original->name, $revision->name);
    }
}
