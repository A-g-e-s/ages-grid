<?php declare(strict_types=1);

namespace Ages\Grid;


enum ActionType
{
    case Cancel;
    case Complete;
    case Link;
    case New;
    case Edit;
    case Show;
    case Delete;
    case Export;
    case Money;
    case Duplicate;
    case Archive;
    case Graph;
    case Print;
    case Info;
    case Update;
    case Email;
}
