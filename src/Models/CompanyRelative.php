<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\CompanyRelativeType;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * @property int $company_id
 * @property bool $is_internal
 * @property null|CompanyRelativeType $type
 * @property null|string $remark
 */
class CompanyRelative extends MorphPivot
{
    protected $table = 'company_relatives';

    protected $casts = [
        'company_id' => 'int',
        'is_internal' => 'bool',
        'type' => CompanyRelativeType::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function stakeholder()
    {
        return $this->morphTo();
    }
}
