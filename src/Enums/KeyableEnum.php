<?php

namespace Creasi\Base\Enums;

use Illuminate\Support\Stringable;

/**
 * @mixin \BackedEnum
 */
trait KeyableEnum
{
    use OptionableEnum;

    /**
     * Retrieve translation key.
     */
    public function key(): Stringable
    {
        assert($this instanceof \BackedEnum, '"KeyableEnum" should only be used in an emun');

        return str($this->name)->snake('-');
    }

    /**
     * Retrieve translated text of the key.
     */
    public function label(): string
    {
        $self = str(static::class)->classBasename()->snake('-');

        return trans("creasi::enums.{$self}.{$this->key()}");
    }
}
