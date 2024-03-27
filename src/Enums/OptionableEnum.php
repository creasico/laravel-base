<?php

namespace Creasi\Base\Enums;

/**
 * @mixin KeyableEnum
 */
trait OptionableEnum
{
    /**
     * Retrieve `cases` of enum as array options.
     *
     * @return array
     */
    public static function toOptions()
    {
        static $options = [];

        if (! empty($options)) {
            return $options;
        }

        foreach (static::cases() as $self) {
            $options[] = $self->toArray();
        }

        return $options;
    }

    /**
     * Retrieve `key` and `value` of enum as an array.
     */
    public function toArray(): array
    {
        $arr = [
            'value' => $this->value,
        ];

        if (\in_array(KeyableEnum::class, \trait_uses_recursive($this), true)) {
            $arr['key'] = (string) $this->key();
            $arr['label'] = $this->label();
        } else {
            $arr['key'] = $this->name;
        }

        return $arr;
    }
}
