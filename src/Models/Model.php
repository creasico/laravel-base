<?php

namespace Creasi\Base\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property null|\Carbon\CarbonImmutable $created_at
 * @property null|\Carbon\CarbonImmutable $updated_at
 * @property null|\Carbon\CarbonImmutable $deleted_at
 */
abstract class Model extends EloquentModel
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory()
    {
        $modelName = \class_basename(static::class);
        $factory = __NAMESPACE__."\\Factories\\{$modelName}Factory";

        return $factory::new();
    }
}
