<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;

class ColumnCheckEmpty extends Column
{

    public function __construct(string $name, ?string $label = null, TextAlign $align = TextAlign::Right)
    {
        parent::__construct($name, $label, $align);
        $this->setTemplate('checkEmpty.latte');
    }

}
