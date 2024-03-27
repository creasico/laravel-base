<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\EmploymentStatus;
use Creasi\Base\Enums\StakeholderStatus;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property ?string $code
 * @property int $business_id
 * @property null|StakeholderType $type
 * @property null|StakeholderStatus $status
 * @property null|EmploymentStatus $employment_status
 * @property null|\Carbon\CarbonImmutable $start_date
 * @property null|\Carbon\CarbonImmutable $finish_date
 * @property-read null|bool $is_started
 * @property-read null|bool $is_finished
 * @property null|string $remark
 * @property-read bool $is_internal
 * @property-read Entity $stakeholder
 */
class BusinessRelative extends MorphPivot
{
    protected $table = 'business_relatives';

    protected $casts = [
        'business_id' => 'int',
        'stakeholder_id' => 'int',
        'type' => StakeholderType::class,
        'status' => StakeholderStatus::class,
        'employment_status' => EmploymentStatus::class,
        'start_date' => 'immutable_date',
        'finish_date' => 'immutable_date',
    ];

    public $timestamps = false;

    public function isInternal(): Attribute
    {
        return Attribute::get(fn () => $this->type->isInternal());
    }

    public function isStarted(): Attribute
    {
        return Attribute::get(fn () => $this->start_date?->isPast());
    }

    public function isFinished(): Attribute
    {
        return Attribute::get(fn () => $this->finish_date?->isPast());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function stakeholder()
    {
        return $this->morphTo();
    }
}
