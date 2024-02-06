<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Concerns\WithAvatar;
use Creasi\Base\Models\Concerns\WithFileUploads;
use Creasi\Base\Models\Contracts\HasFileUploads;
use Creasi\Base\Models\Contracts\Stakeholder;
use Creasi\Nusa\Contracts\HasAddresses;
use Creasi\Nusa\Models\Concerns\WithAddresses;

/**
 * @property string $name
 * @property null|string $alias
 * @property null|string $email
 * @property null|string $phone
 * @property null|string $summary
 */
abstract class Entity extends Model implements HasAddresses, HasFileUploads, Stakeholder
{
    use WithAddresses;
    use WithAvatar;
    use WithFileUploads;

    public function getFillable()
    {
        return \array_merge(parent::getFillable(), [
            'name',
            'alias',
            'email',
            'phone',
            'summary',
        ]);
    }
}
