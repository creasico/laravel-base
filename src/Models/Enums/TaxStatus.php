<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

enum TaxStatus: int
{
    use KeyableEnum;

    case TK0 = 1;
    case TK1 = 2;
    case TK2 = 3;
    case TK3 = 4;
    case K0 = 5;
    case K1 = 6;
    case K2 = 7;
    case K3 = 8;
    case KI0 = 9;
    case KI1 = 10;
    case KI2 = 11;
    case KI3 = 12;
}
