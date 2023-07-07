<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\EmploymentStatus;
use Creasi\Base\Models\Enums\EmploymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property bool $is_primary
 * @property null|EmploymentType $type
 * @property null|EmploymentStatus $status
 * @property null|\Carbon\CarbonImmutable $start_date
 * @property null|\Carbon\CarbonImmutable $finish_date
 * @property null|string $remark
 * @property null|bool $is_started
 * @property null|bool $is_finished
 */
class Employment extends Pivot
{
    protected $casts = [
        'is_primary' => 'bool',
        'type' => EmploymentType::class,
        'status' => EmploymentStatus::class,
        'start_date' => 'immutable_date',
        'finish_date' => 'immutable_date',
    ];

    public function isStarted(): Attribute
    {
        return Attribute::get(fn () => $this->start_date?->isPast());
    }

    public function isFinished(): Attribute
    {
        return Attribute::get(fn () => $this->finish_date?->isPast());
    }
}
