<?php declare(strict_types=1);

namespace Ages\Grid\Column;


enum BooleanType
{
    case Default;
    case Visibility;
    case Trash;
    case Error;
}
