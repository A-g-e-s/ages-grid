<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;

class ColumnFunction extends Column
{
    /**
     * @var FunctionParam[]
     */
    private array $functionParams;

    /**
     * @param string      $function
     * @param string|null $label
     * @param TextAlign   $align
     */
    public function __construct(string $function, ?string $label = null, TextAlign $align = TextAlign::Left)
    {
        parent::__construct($function, $label, $align);
    }

    public function addFunctionParam(string|int $param, bool $fromData = false): self
    {
        $this->functionParams[] = new FunctionParam($param, $fromData);
        return $this;
    }

    /**
     * @return FunctionParam[]
     */
    public function getFunctionParams(): array
    {
        return $this->functionParams;
    }

}
