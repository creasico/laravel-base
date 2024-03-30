<?php

namespace Creasi\Base\Database\Factories\Concerns;

use Creasi\Base\Database\Factories\PersonFactory;

/**
 * @mixin \Illuminate\Database\Eloquent\Factories\Factory
 */
trait WithProfile
{
    public function withProfile(PersonFactory $profile): static
    {
        return $this->has($profile->state(fn ($_, $user) => [
            'name' => $user->name,
            'email' => $user->email,
        ]), 'profile');
    }
}
