<?php declare(strict_types=1);

namespace Ages\Grid\Column;


enum DateType: string
{
    case YearMonth = 'Y-m';
    case Date = 'd. m. y';
    case DateShort = 'd.m';
    case Time = 'H:i';
    case DateTime = 'd. m. y H:i';
    case DateTimeShort = 'd.m. H:i';
}
