<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\BusinessRelativeType;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property ?string $code
 * @property int $business_id
 * @property bool $is_internal
 * @property null|BusinessRelativeType $type
 * @property null|string $remark
 * @property-read Entity $stakeholder
 */
class BusinessRelative extends MorphPivot
{
    protected $table = 'business_relatives';

    protected $casts = [
        'business_id' => 'int',
        'stakeholder_id' => 'int',
        'is_internal' => 'bool',
        'type' => BusinessRelativeType::class,
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function stakeholder()
    {
        return $this->morphTo();
    }
}
