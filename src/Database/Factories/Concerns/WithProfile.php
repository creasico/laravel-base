<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Models\Person;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithProfile
{
    public function withIdentity(?\Closure $cb = null): static
    {
        if ($cb === null) {
            $cb = fn ($profile) => $profile;
        }

        return $this->has($cb(Person::factory()), 'profile');
    }
}
