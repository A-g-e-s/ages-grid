<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


class ColumnImage extends Column
{

    public function __construct(
        string                     $name,
        ?string                    $label = null,
        private readonly ImageType $type = ImageType::Medium,
        private readonly bool      $isRelative = true,
    )
    {
        parent::__construct($name, $label);
        $this->setTemplate('image.latte');
    }

    public function getType(): ImageType
    {
        return $this->type;
    }

    public function isRelative(): bool
    {
        return $this->isRelative;
    }

}
