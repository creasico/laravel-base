<?php

namespace Creasi\Base\Enums;

use Faker\Provider\Person;

/**
 * Only binary-type genders are supported here.
 */
enum Gender: string
{
    use KeyableEnum;

    case Male = 'M';
    case Female = 'F';

    /**
     * Convert the value to "Faker" value, so it can be used in factories.
     */
    public function toFaker(): string
    {
        return match ($this) {
            self::Male => Person::GENDER_MALE,
            self::Female => Person::GENDER_FEMALE,
        };
    }
}
