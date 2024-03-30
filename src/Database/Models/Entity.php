<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Model;
use Creasi\Base\Database\Models\Concerns\WithAvatar;
use Creasi\Base\Database\Models\Concerns\WithFiles;
use Creasi\Base\Database\Models\Contracts\HasFiles;
use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Nusa\Contracts\HasAddresses;
use Creasi\Nusa\Models\Concerns\WithAddresses;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $name
 * @property null|string $alias
 * @property null|string $email
 * @property null|string $phone
 * @property null|string $summary
 */
abstract class Entity extends Model implements HasAddresses, HasFiles, Stakeholder
{
    use HasUuids;
    use WithAddresses;
    use WithAvatar;
    use WithFiles;

    /**
     * {@inheritdoc}
     */
    public function getFillable()
    {
        return \array_merge(parent::getFillable(), [
            'uuid',
            'name',
            'alias',
            'email',
            'phone',
            'summary',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function uniqueIds()
    {
        return [$this->getRouteKeyName()];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
