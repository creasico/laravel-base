<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

enum PersonnelRelativeStatus: int
{
    use KeyableEnum;

    case Child = 1;
    case Spouse = 2;
    case Sibling = 3;
    case SiblingsChild = 4;
    case Parent = 5;
    case ParentsSibling = 6;
    case Grandparent = 7;
    case Grandchild = 8;
    case Cousin = 9;
}
