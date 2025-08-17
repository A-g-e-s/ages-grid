<?php declare(strict_types=1);

namespace Ages\Grid\Column;


enum SummaryType
{
    case Avg;
    case Sum;
    case Min;
    case Max;
}
