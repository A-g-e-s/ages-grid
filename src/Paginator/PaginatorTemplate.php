<?php

declare(strict_types=1);


namespace Ages\Grid\Paginator;

use Ages\Grid\Styles\GridStyleInterface;
use Nette\Bridges\ApplicationLatte\Template;

class PaginatorTemplate extends Template
{
    public Paginator $paginator;
    /**
     * @var int[]
     */
    public array $itemsOptions;
    public bool $show;
    /**
     * @var int[]
     */
    public array $steps;

    public GridStyleInterface $gs;

}
