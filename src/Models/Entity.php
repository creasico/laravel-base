<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\HasFileUploads;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Concerns\WithAvatar;
use Creasi\Base\Models\Concerns\WithFileUploads;
use Creasi\Nusa\Contracts\HasAddresses;
use Creasi\Nusa\Models\Concerns\WithAddresses;

/**
 * @property null|string $code
 * @property string $name
 * @property null|string $email
 * @property null|string $phone_number
 * @property null|string $summary
 */
abstract class Entity extends Model implements HasAddresses, HasFileUploads, Stakeholder
{
    use WithAddresses;
    use WithAvatar;
    use WithFileUploads;

    public function getFillable()
    {
        return \array_merge($this->fillable, [
            'code',
            'name',
            'email',
            'phone_number',
            'summary',
        ]);
    }
}
