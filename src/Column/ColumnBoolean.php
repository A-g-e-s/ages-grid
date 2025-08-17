<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;

class ColumnBoolean extends Column
{
    private BooleanType $type;

    public function __construct(string $name, ?string $label = null, BooleanType $type = BooleanType::Default, TextAlign $align = TextAlign::Right)
    {
        parent::__construct($name, $label, $align);
        $this->setTemplate('boolean.latte');
        $this->type = $type;
    }

    public function getType(): BooleanType
    {
        return $this->type;
    }

}
