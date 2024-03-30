<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\PersonnelStatus;
use Creasi\Base\Enums\StakeholderStatus;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property ?string $code
 * @property int $organization_id
 * @property null|StakeholderType $type
 * @property null|StakeholderStatus $status
 * @property null|PersonnelStatus $personnel_status
 * @property null|\Carbon\CarbonImmutable $start_date
 * @property null|\Carbon\CarbonImmutable $finish_date
 * @property-read null|bool $is_started
 * @property-read null|bool $is_finished
 * @property null|string $remark
 * @property-read bool $is_internal
 * @property-read Entity $stakeholder
 */
class OrganizationRelative extends MorphPivot
{
    protected $table = 'organizations_relatives';

    protected $casts = [
        'organization_id' => 'int',
        'stakeholder_id' => 'int',
        'type' => StakeholderType::class,
        'status' => StakeholderStatus::class,
        'personnel_status' => PersonnelStatus::class,
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
