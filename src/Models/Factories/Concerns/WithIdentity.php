<?php

namespace Creasi\Base\Models\Factories\Concerns;

use Creasi\Base\Models\Personnel;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithIdentity
{
    public function withIdentity(?\Closure $cb = null): static
    {
        if ($cb === null) {
            $cb = fn ($identity) => $identity->withProfile();
        }

        return $this->has($cb(Personnel::factory()), 'identity');
    }
}