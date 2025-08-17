<?php

declare(strict_types=1);

namespace Ages\Grid\Column;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<mixed, Column>
 */
class Collection implements IteratorAggregate, Countable
{
    /** @var array<int|string,Column> */
    protected array $data = [];

    public function add(Column $column): void
    {
        if ($this->columnExist($column->getName())) {
            $suffix = 1;
            while ($this->columnExist(sprintf('%s__%s', $column->getName(), $suffix))) {
                $suffix++;
            }
            $column->setAlphaNumericName((string)$suffix);
            $this->data[sprintf('%s__%s', $column->getName(), $suffix)] = $column;
            return;
        }
        $this->data[$column->getName()] = $column;
    }

    public function columnExist(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /** @return ArrayIterator<int|string, Column> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    public function empty(): bool
    {
        return !(($this->count() > 0));
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getColumnByAlphanumericName(string $name): ?Column
    {
        foreach ($this->data as $column) {
            if ($column->getAlphaNumericName() === $name) {
                return $column;
            }
        }
        return null;
    }

    /**
     * public function get(string $key): Column
     * {
     * return $this->data[$key];
     * }
     */
    public function showFilterRow(): bool
    {
        foreach ($this->data as $column) {
            if ($column->isFilterable()) {
                return true;
            }
        }
        return false;
    }

    public function showSummaryRow(): bool
    {
        foreach ($this->data as $column) {
            if ($column instanceof ColumnNumber && $column->hasSummary()) {
                return true;
            }
        }
        return false;
    }
}
