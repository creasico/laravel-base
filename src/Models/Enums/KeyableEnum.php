<?php

namespace Creasi\Base\Models\Enums;

use Illuminate\Support\Stringable;

/**
 * @mixin \BackedEnum
 */
trait KeyableEnum
{
    /**
     * Retrieve translation key.
     */
    public function key(): Stringable
    {
        assert($this instanceof \BackedEnum, '"KeyableEnum" should only be used in an emun');

        return str($this->name)->slug();
    }

    /**
     * Retrieve translated text of the key.
     */
    public function label(): string
    {
        $self = str(static::class)->classBasename()->slug();

        return trans("creasico::base.{$self}.{$this->key()}");
    }
}
