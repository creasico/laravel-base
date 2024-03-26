<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\BusinessRelativeType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property ?string $code
 * @property int $business_id
 * @property null|BusinessRelativeType $type
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
        'type' => BusinessRelativeType::class,
    ];

    public $timestamps = false;

    public function isInternal(): Attribute
    {
        return Attribute::get(fn () => $this->type->isInternal());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function stakeholder()
    {
        return $this->morphTo();
    }
}
