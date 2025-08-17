<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Nextras\Orm\Entity\IEntity;

class ColumnFile extends Column
{
    private bool $directDownload;

    public function __construct(
        string $name,
        ?string $label = null,
        bool $directDownload = false
    ) {
        parent::__construct($name, $label);
        $this->setTemplate('file.latte');
        $this->directDownload = $directDownload;
    }

    public function getFilename(IEntity $row): ?string
    {
        $property = $this->getColumnRawValue($row);
        if (is_string($property) && strlen($property) > 0) {
            return basename($property);
        }
        return null;
    }

    public function isDirectDownload(): bool
    {
        return $this->directDownload;
    }


}
