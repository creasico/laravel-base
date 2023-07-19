<?php

namespace Creasi\Base\Models\Concerns;

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
            'tax_status' => 'int',
        ]);

        $this->mergeFillable([
            'tax_status',
            'tax_id',
        ]);
    }
}
