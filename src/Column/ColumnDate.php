<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;

class ColumnDate extends Column
{
    private bool $dateRangeFilter = false;

    public function __construct(string $name, ?string $label, private readonly DateType $dateType, TextAlign $align)
    {
        parent::__construct($name, $label, $align);
        $this->classes = 'whitespace-nowrap';
    }

    public function getDateType(): DateType
    {
        return $this->dateType;
    }

    public function setFilterable(): static
    {
        parent::setFilterable();
        $this->dateRangeFilter = true;
        return $this;
    }

    public function setDateRangeFilter(): static
    {
        $this->dateRangeFilter = true;
        return $this;
    }

    public function isDateRangeFilter(): bool
    {
        return $this->dateRangeFilter;
    }

}
