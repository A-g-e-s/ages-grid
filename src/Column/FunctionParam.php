<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


class FunctionParam
{
    public function __construct(
        public string|int|bool $value,
        public bool $data
    ) {
    }
}
