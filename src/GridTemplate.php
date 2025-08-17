<?php

declare(strict_types=1);


namespace Ages\Grid;

use Ages\Grid\Column\Collection;
use Ages\Grid\Column\Column;
use Nette\Bridges\ApplicationLatte\Template;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;

/**
 * @template T of IEntity
 */
class GridTemplate extends Template
{
    public string $basePath;

    /** @var Action[] */
    public array $actions;

    /** @var HeaderAction[] */
    public array $headerActions;

    public bool $actionColumn;

    public ?string $caption;

    /** @var Collection<Column> */
    public Collection $collection;

    /** @var ICollection<T> */
    public ICollection $data;

    public bool $showFilter;

    public int $columnsCount;

    public bool $hoverable;

    public bool $exportMode;

    public string $exportName;

    public bool $export;

}
