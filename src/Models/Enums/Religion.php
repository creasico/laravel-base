<?php

namespace Creasi\Base\Models\Enums;

enum Religion: int
{
    use KeyableEnum;

    case Other = 0;
    case Islam = 1;
    case Christian = 2;
    case Catholic = 3;
    case Hinduism = 4;
    case Buddhism = 5;
    case Confucianism = 6;
}
