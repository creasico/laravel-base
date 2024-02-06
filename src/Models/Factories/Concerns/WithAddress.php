<?php

namespace Creasi\Base\Models\Factories\Concerns;

use Creasi\Base\Models\Address;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithAddress
{
    public function withAddress(): static
    {
        return $this->has(Address::factory());
    }
}
