<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;
use Nextras\Orm\Entity\IEntity;

/**
 * Progress bar z hodnoty v procentech (0–100), prázdná hodnota (null) se nevykreslí.
 */
class ColumnProgress extends Column
{
    private string $barClass = 'bg-sky-500';
    private string $doneBarClass = 'bg-emerald-500';

    public function __construct(string $name, ?string $label = null, TextAlign $align = TextAlign::Left)
    {
        parent::__construct($name, $label, $align);
        $this->setTemplate('progress.latte');
    }

    public function setBarClass(string $barClass, ?string $doneBarClass = null): static
    {
        $this->barClass = $barClass;
        $this->doneBarClass = $doneBarClass ?? $barClass;
        return $this;
    }

    public function getPercent(IEntity $row): ?float
    {
        $value = $this->getColumnRawValue($row);
        if (!is_numeric($value)) {
            return null;
        }
        return max(0.0, min(100.0, (float) $value));
    }

    public function getBarClass(float $percent): string
    {
        return $percent >= 100.0 ? $this->doneBarClass : $this->barClass;
    }
}
