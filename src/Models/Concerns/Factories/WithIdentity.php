<?php

namespace Creasi\Base\Models\Concerns\Factories;

use Creasi\Base\Models\Personnel;
use Database\Factories\PersonnelFactory;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithIdentity
{
    public function withIdentity(\Closure $cb = null): static
    {
        if ($cb === null) {
            $cb = fn (PersonnelFactory $identity) => $identity->withProfile();
        }

        return $this->has($cb(Personnel::factory()), 'identity');
    }
}
