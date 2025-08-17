<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


class LinkParam
{
    public function __construct(
        public string $key,
        public string|int|bool $value,
        public bool $data
    ) {
    }
}
