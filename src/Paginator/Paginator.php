<?php

declare(strict_types=1);


namespace Ages\Grid\Paginator;


use Ages\Grid\Exception\InvalidArgument;
use Ages\Grid\Exception\UnexpectedUse;
use Ages\Grid\Grid;
use Ages\Grid\Styles\GridStyleInterface;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Control;

/**
 * @property PaginatorTemplate $template
 */
class Paginator extends Control
{

    #[Persistent]
    public int $itemsPerPage = 0;
    private string $templateFile = __DIR__ . '/template/paginator.latte';
    /** @var int[] */
    private array $itemsPerPageOption = [80, 160, 240];
    private bool $show = false;

    public function __construct(
        private readonly GridStyleInterface $gridStyle,
        private readonly \Nette\Utils\Paginator $paginator = new \Nette\Utils\Paginator())
    {
        $this->paginator->setPage(1);
        if ($this->itemsPerPage === 0) {
            $this->setItemsPerPageOptionOffset(1);
        } else {
            $this->setItemsPerPage($this->itemsPerPage);
        }
    }

    public function setPage(int $page): self
    {
        $this->paginator->setPage($page);
        return $this;
    }

    public function setItemsPerPageOptionOffset(int $offset): self
    {
        $key = --$offset;
        if (array_key_exists($key, $this->itemsPerPageOption)) {
            $this->itemsPerPage = $this->itemsPerPageOption[$key];
        } else {
            $this->itemsPerPage = $this->itemsPerPageOption[0];
        }
        $this->paginator->setItemsPerPage($this->itemsPerPage);
        return $this;
    }

    public function handlePaginate(int $page): void
    {
        $this->paginator->setPage($page);
        $this->redraw();
    }

    private function redraw(): void
    {
        $grid = $this->getParent();
        if (!$grid instanceof Grid) {
            throw new UnexpectedUse();
        }
        $grid->redrawControl('grid');
        $this->redrawControl('paginator');
    }

    public function handleItems(int $items): void
    {
        if ($items <= 0) {
            throw new InvalidArgument('Value has to be greater than 0');
        } else {
            $this->itemsPerPage = $items;
        }
        $this->redraw();
    }

    public function setTemplateFile(string $template): void
    {
        $this->templateFile = $template;
    }

    public function getPaginator(): self
    {
        return $this;
    }

    public function setItemCount(int $count): self
    {
        $this->paginator->setItemCount($count);
        return $this;
    }

    public function getPage(): int
    {
        return $this->paginator->getPage();
    }

    /**
     * @param int[] $options
     * @return void
     */
    public function setItemsPerPageOptions(array $options): void
    {
        $this->itemsPerPageOption = $options;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage(int $count): self
    {
        if ($count > 0) {
            $this->paginator->setItemsPerPage($count);
        }
        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->paginator->getPageCount();
    }

    public function getItemCount(): ?int
    {
        return $this->paginator->getItemCount();
    }

    public function getOffset(): int
    {
        return $this->paginator->getOffset();
    }

    public function setVisible(bool $visible = true): self
    {
        $this->show = $visible;
        return $this;
    }

    public function render(): void
    {
        $this->template->getLatte()->setStrictParsing(false);
        $this->template->setFile($this->templateFile);
        $this->template->paginator = $this;
        $this->template->itemsOptions = $this->itemsPerPageOption;
        $this->template->show = $this->show;
        $this->template->gs = $this->gridStyle;
        $this->template->steps = $this->calculateSteps();
        $this->template->render();
    }

    /**
     * @return int[]
     */
    private function calculateSteps(): array
    {
        if ($this->paginator->pageCount < 2) {
            $steps = [1];
        } else {
            $start = max($this->paginator->firstPage, $this->paginator->page - 2);
            $end = (int)min($this->paginator->lastPage, $this->paginator->page + 2);
            $range = range($start, $end);
            $count = 1;
            $perPage = $this->paginator->pageCount;
            $quotient = ($perPage - 1) / $count;
            for ($i = 0; $i <= $count; $i++) {
                $range[] = (int)round($quotient * $i) + $this->paginator->firstPage;
            }
            sort($range);
            $steps = array_values(array_unique($range));
        }
        return $steps;
    }
}
