<?php

namespace Creasi\Base\Models\Enums;

/**
 * @mixin KeyableEnum
 */
trait OptionableEnum
{
    public static function toOptions()
    {
        static $options = [];

        if (! empty($options)) {
            return $options;
        }

        foreach (static::cases() as $self) {
            $option = [
                'value' => $self->value,
            ];

            if (\in_array(KeyableEnum::class, \trait_uses_recursive($self), true)) {
                $option['key'] = (string) $self->key();
                $option['label'] = $self->label();
            } else {
                $option['key'] = $self->name;
            }

            $options[] = $option;
        }

        return $options;
    }
}
