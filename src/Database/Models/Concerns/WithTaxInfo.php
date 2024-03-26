<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Enums\TaxStatus;

/**
 * @mixin \Creasi\Base\Database\Models\Contracts\HasTaxInfo
 */
trait WithTaxInfo
{
    /**
     * Initialize the trait.
     */
    public function initializeWithTaxInfo(): void
    {
        $this->mergeCasts([
            'tax_status' => TaxStatus::class,
        ]);

        $this->mergeFillable([
            'tax_status',
            'tax_id',
        ]);
    }
}
