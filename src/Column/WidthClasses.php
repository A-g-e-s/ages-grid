<?php declare(strict_types=1);

namespace Ages\Grid\Column;


enum WidthClasses: string
{
    case Boolean = 'w-8';
    case Default = '';
    case Fit = 'w-fit';
}
