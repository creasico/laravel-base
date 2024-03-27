<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Models\Address;

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
