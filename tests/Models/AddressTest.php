<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Database\Models\Address;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('address')]
class AddressTest extends TestCase
{
    public static function sampleData(): array
    {
        return [
            [10, 5, ['010', '005']],
        ];
    }

    #[Test]
    #[DataProvider('sampleData')]
    public function should_normalize_rt_rw_value(int $rt, int $rw, array $expected): void
    {
        $model = Address::factory()->createOne([
            'rt' => $rt,
            'rw' => $rw,
        ]);

        $this->assertSame($expected, [$model->rt, $model->rw]);
    }
}
