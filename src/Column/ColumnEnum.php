<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\Exception\InvalidArgument;
use Ages\Grid\Exception\InvalidEnumClass;
use Ages\Grid\TextAlign;

class ColumnEnum extends Column
{
    private string $enumClass;

    public function __construct(string $name, ?string $label, string $enumClass, TextAlign $align = TextAlign::Right)
    {
        parent::__construct($name, $label, $align);
        $this->enumClass = $enumClass;
        if (!is_subclass_of($enumClass, \BackedEnum::class)) {
            throw new InvalidEnumClass('Please provide Backed Enum class');
        }
    }

    public function getEnumClass(): string
    {
        return $this->enumClass;
    }

    public function parseValue(string $value): \BackedEnum
    {
        $enumClass = $this->enumClass;
        if (is_subclass_of($enumClass, \BackedEnum::class)) {
            $value =  $enumClass::tryFrom($value);
            if ($value !== null){
                return $value;
            }


        }
        throw new InvalidArgument("Invalid value '$value' for enum $enumClass");
    }

}
