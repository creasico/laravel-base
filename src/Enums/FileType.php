<?php

namespace Creasi\Base\Enums;

enum FileType: int
{
    use KeyableEnum;

    case Other = 0;
    case Document = 1;
    case Image = 2;
    case Logo = 3;
    case Avatar = 4;
}
