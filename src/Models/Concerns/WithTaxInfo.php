<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Enums\TaxStatus;

/**
 * @mixin \Creasi\Base\Contracts\HasTaxInfo
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
