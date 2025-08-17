<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\Exception\InvalidArgument;
use Ages\Grid\TextAlign;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;

class ColumnNumber extends Column
{

    private int $decimal = 0;
    private string $decimalSep = '.';
    private string $thousandsSep = ' ';
    private bool $summary = false;
    private SummaryType $summaryType = SummaryType::Sum;

    public function __construct(string $name, ?string $label = null, TextAlign $align = TextAlign::Right, ?string $unit = null, bool $unitData = false)
    {
        parent::__construct($name, $label, $align, $unit, $unitData);
        $this->classes = 'whitespace-nowrap';
    }

    public function getColumnValue(IEntity $row): string
    {
        $property = $this->getColumnRawValue($row);
        if ($property instanceof \DateTimeImmutable) {
            throw new InvalidArgument('Column number should be used only for numeric data.');
        }
        $property = floatval($property);
        $value = $this->formatValue($property);
        $unit = $this->getUnit($row);
        return sprintf('%s%s', $value, $unit);
    }

    private function formatValue(float $value): string
    {
        return number_format(
            $value,
            $this->decimal,
            $this->decimalSep,
            $this->thousandsSep
        );
    }

    public function setFormat(
        int $decimal = 0,
        string $decimalSep = ',',
        string $thousandsSep = ' '
    ): self {
        $this->decimal = $decimal;
        $this->decimalSep = $decimalSep;
        $this->thousandsSep = $thousandsSep;
        return $this;
    }

    /** ******************** Summary ******************** **/

    public function hasSummary(): bool
    {
        return $this->summary;
    }

    public function addSummary(SummaryType $summaryType = SummaryType::Sum): self
    {
        $this->summary = true;
        $this->summaryType = $summaryType;
        return $this;
    }

    /**
     * @param ICollection<IEntity> $collection
     * @return string
     */
    public function summary(ICollection $collection): string
    {
        $value = match ($this->summaryType) {
            SummaryType::Sum => $this->sum($collection),
            SummaryType::Avg => $this->avg($collection),
            SummaryType::Min => $this->min($collection),
            SummaryType::Max => $this->max($collection)
        };
        return ($this->unit !== null && $this->unitFromData() === false) ? sprintf('%s %s', $value, $this->unit) : $value;
    }

    /**
     * @param ICollection<IEntity> $collection
     * @return string
     */
    public function sum(ICollection $collection): string
    {
        $sum = 0;
        foreach ($collection as $row) {
            $sum += $this->getEntityProperty($row, $this->getName());
        }
        $value = floatval($sum);
        return sprintf('Σ %s', $this->formatValue($value));
    }

    /**
     * @param ICollection<IEntity> $collection
     * @return string
     */
    public function avg(ICollection $collection): string
    {
        $sum = 0;
        $c = 0;
        foreach ($collection as $row) {
            $sum += $this->getEntityProperty($row, $this->getName());
            $c++;
        }
        $value = $c === 0 ? 0 : floatval($sum / $c);
        return sprintf('Ø %s', $this->formatValue($value));
    }

    /**
     * @param ICollection<IEntity> $collection
     * @return string
     */
    public function min(ICollection $collection): string
    {
        $numbers = [];
        foreach ($collection as $row) {
            $property = $this->getEntityProperty($row, $this->getName());
            if ($property instanceof \DateTimeImmutable) {
                throw new InvalidArgument('Column number should be used only for numeric data.');
            }
            $numbers[] = $property;
        }
        $value = floatval(min($numbers));
        return sprintf('↓ %s', $this->formatValue($value));
    }

    /**
     * @param ICollection<IEntity> $collection
     * @return string
     */
    public function max(ICollection $collection): string
    {
        $numbers = [];
        foreach ($collection as $row) {
            $property = $this->getEntityProperty($row, $this->getName());
            if ($property instanceof \DateTimeImmutable) {
                throw new InvalidArgument('Column number should be used only for numeric data.');
            }
        }
        $value = floatval(max($numbers));
        return sprintf('↑ %s', $this->formatValue($value));
    }

}
