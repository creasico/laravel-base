<?php

namespace Creasi\Base\Database\Models\Concerns;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $primaryEmployer
 *
 * @mixin \Creasi\Base\Database\Models\Contracts\Personnel
 */
trait AsPersonnel
{
    /**
     * Initialize the trait.
     */
    final protected function initializeAsPersonnel(): void
    {
        // $this->append('employer');

        // $this->makeHidden('primaryEmployer');
    }
}
