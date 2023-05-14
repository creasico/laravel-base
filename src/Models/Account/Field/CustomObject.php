<?php

namespace Creasi\Laravel\Models\Account\Field;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

class CustomObject implements CastsAttributes
{
    /**
     * {@inheritdoc}
     */
    public function get($model, $key, $value, $attributes)
    {
        if (! isset($attributes[$key])) {
            return;
        }

        $data = json_decode($attributes[$key], true);

        return is_array($data)
            ? $this->makeCastableObject($model, $attributes['cast'], $data)
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($model, $key, $value, $attributes)
    {
        return [$key => json_encode($value)];
    }

    /**
     * @param \Creasi\Laravel\Accounts\Field $model
     * @param Cast $cast
     * @param array $data
     * @return ArrayObject
     */
    private function makeCastableObject($model, Cast $cast, array $data)
    {
        dump($model);
        return match($cast) {
            default => new ArrayObject($data)
        };
    }
}
