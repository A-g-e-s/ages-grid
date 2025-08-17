<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;

class ColumnDate extends Column
{


    public function __construct(string $name, ?string $label, private readonly DateType $dateType, TextAlign $align)
    {
        parent::__construct($name, $label, $align);
        $this->classes = 'whitespace-nowrap';
    }

    public function getDateType(): DateType
    {
        return $this->dateType;
    }

}
