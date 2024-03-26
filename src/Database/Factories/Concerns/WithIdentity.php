<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Models\Personnel;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithIdentity
{
    public function withIdentity(?\Closure $cb = null): static
    {
        if ($cb === null) {
            $cb = fn ($identity) => $identity;
        }

        return $this->has($cb(Personnel::factory()), 'identity');
    }
}
