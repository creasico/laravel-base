<?php

namespace Creasi\Base\Models\Enums;

/**
 * Only binary-type genders are supported here.
 */
enum FileUploadType: int
{
    use KeyableEnum;

    case Avatar = 0;
    case Logo = 1;
    case Image = 2;
    case Document = 3;
}
