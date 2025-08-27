<?php

declare(strict_types=1);


namespace Ages\Grid;

use Ages\Grid\Column\BooleanType;
use Ages\Grid\Column\Collection;
use Ages\Grid\Column\Column;
use Ages\Grid\Column\ColumnBoolean;
use Ages\Grid\Column\ColumnCheckEmpty;
use Ages\Grid\Column\ColumnDate;
use Ages\Grid\Column\ColumnEnum;
use Ages\Grid\Column\ColumnFile;
use Ages\Grid\Column\ColumnFunction;
use Ages\Grid\Column\ColumnImage;
use Ages\Grid\Column\ColumnNumber;
use Ages\Grid\Column\ColumnString;
use Ages\Grid\Column\DateType;
use Ages\Grid\Column\ImageType;
use Ages\Grid\Exception\InvalidArgument;
use Ages\Grid\Paginator\Paginator;
use Ages\Grid\Styles\GridStyle;
use Ages\Grid\Styles\GridStyleInterface;
use Nette\Application\Attributes\Persistent;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\Expression\LikeExpression;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property GridTemplate $template
 * @method onExport(string $name)
 * @template T of IEntity
 */
final class Grid extends UI\Control
{
    const ExportName = 'Export';
    #[Persistent]
    public int $page = 1;
    #[Persistent]
    public ?string $columnSort = null;
    #[Persistent]
    public string $sortOrder = ICollection::ASC_NULLS_LAST;
    /** @var array<string, mixed> */
    #[Persistent]
    public array $appliedFilter = [];
    /**
     * @var array|callable[]
     */
    public $onExport = [];
    protected ?string $caption = null;
    /** @var Collection<Column> */
    protected Collection $collection;

    /** @var ICollection<T> */
    private ICollection $data;
    private string $templateFile = __DIR__ . '/template/grid.latte';
    private Paginator $paginator;
    /**
     * @var array<int, Action|ActionCallback>
     */
    private array $actions = [];
    /** @var HeaderAction[] */
    private array $headerActions = [];
    private bool $actionColumn = false;
    private bool $hoverable = false;
    private bool $export = false;
    private bool $exportMode = false;
    private string $exportName = self::ExportName;

    private GridStyleInterface $gridStyle;


    /**
     * @param ICollection<T>|OneHasMany<T> $rawData
     */
    public function __construct(
        ICollection|OneHasMany $rawData,
        ?GridStyleInterface $gridStyle = null
    ) {
        if ($rawData instanceof OneHasMany) {
            /** @var ICollection<T> $c */
            $c = $rawData->toCollection();
            $this->data = $c;
        } else {
            /** @var ICollection<T> $rawData */
            $this->data = $rawData;
        }
        $this->gridStyle = $gridStyle ?? new GridStyle();
        $this->paginator = new Paginator($this->gridStyle);
        $this->collection = new Collection();
    }

    public function handleSort(string $column): void
    {
        if ($this->columnSort === $column && $this->sortOrder === ICollection::DESC_NULLS_LAST) {
            $this->sortOrder = ICollection::ASC_NULLS_LAST;
        } else {
            $this->sortOrder = ICollection::DESC_NULLS_LAST;
        }
        $this->columnSort = $column;
        $this->redrawControl('grid');
    }

    public function handleExport(?string $basePath = null, ?string $exportPath = null): void
    {
        $e = new Export(
                        $this->collection,
                        $this->sortData(),
                        $this->caption,
            basePath:   $basePath,
            exportPath: $exportPath
        );
        $name = $e->exportData();
        $this->onExport($name);
        $this->getPresenter()->sendResponse(new FileResponse($e->getExportPath() . $name));
    }

    /**
     * @return ICollection<T>
     */
    private function sortData(): ICollection
    {
        $data = $this->filterData()->resetOrderBy();
        if ($this->columnSort !== null) {
            $data = $data->orderBy($this->columnSort, $this->sortOrder);
        }
        return $data;
    }

    /** ******************** Internal ******************** **/
    /**
     * @return ICollection<T>
     */
    private function filterData(): ICollection
    {
        $data = $this->data;

        foreach ($this->appliedFilter as $key => $rawValue) {
            if (!is_scalar($rawValue) && $rawValue !== null) {
                continue;
            }

            $value = (string)$rawValue;
            $column = $this->collection->getColumnByAlphanumericName($key);

            if ($column instanceof ColumnEnum) {
                try {
                    $enumValue = $column->parseValue($value);
                    $data = $data->findBy([$key => $enumValue]);
                } catch (InvalidArgument) {
                }
                continue;
            }

            $operator = match (true) {
                str_starts_with($value, '>') => '>',
                str_starts_with($value, '<') => '<',
                str_starts_with($value, '=') => '=',
                str_starts_with($value, '!') => '!=',
                default => '~',
            };

            $parsed = $this->parseValueByOperator($value, $operator);

            if (strtolower($parsed) === 'null') {
                $parsed = null;
            }

            if ($operator === '~' && $parsed !== null) {
                $data = $data->findBy([sprintf('%s~', $key) => LikeExpression::contains($parsed)]);
            } else {
                $data = $data->findBy([sprintf('%s%s', $key, $operator) => $parsed]);
            }
        }

        return $data;
    }

    private function parseValueByOperator(string $value, string $operator): string
    {
        return match ($operator) {
            '>', '<', '=', '!=' => Strings::after($value, $operator) ?? '',
            default => $value,
        };
    }

    public function handleActionCallback(int $key, int $primary): void
    {
        $act = $this->actions[$key];
        assert($act instanceof ActionCallback);
        $act->callback($primary);
    }

    public function setSortColumn(string $column): void
    {
        $this->columnSort = $column;
    }

    public function addExport(?string $exportModeName = null): void
    {
        if ($exportModeName !== null) {
            $this->exportMode = true;
            $this->exportName = $exportModeName;
        }
        $this->export = true;
    }

    public function render(): void
    {
        $this->template->getLatte()->setStrictParsing(false);
        $this->template->setFile($this->templateFile);
        $this->template->exportMode = $this->exportMode;
        $this->template->gs = $this->gridStyle;
        if ($this->exportMode === true) {
            $this->template->exportName = $this->exportName;
        } else {
            $this->template->caption = $this->caption;
            $this->template->collection = $this->collection;
            $this->template->data = $this->getData();
            $this->template->actionColumn = $this->actionColumn;
            $this->template->actions = $this->actions;
            $this->template->headerActions = $this->headerActions;
            $this->template->columnsCount = $this->columnsCount();
            $this->template->showFilter = $this->collection->showFilterRow();
            $this->template->hoverable = $this->hoverable;
            $this->template->export = $this->export;
        }
        $this->template->render();
    }

    /** @return ICollection<T> */
    private function getData(): ICollection
    {
        $data = $this->sortData();
        $this->paginator->setItemCount($data->count());
        $this->paginator->setItemsPerPage($this->paginator->getItemsPerPage());
        return $data->limitBy($this->paginator->getItemsPerPage(), $this->paginator->getOffset());
    }

    protected function columnsCount(): int
    {
        $columns = $this->collection->count();
        return $this->actionColumn ? ++$columns : $columns;
    }

    public function reverseSort(): void
    {
        $this->sortOrder = ICollection::DESC_NULLS_FIRST;
    }

    /** ******************** Render ******************** **/
    public function setHoverable(): void
    {
        $this->hoverable = true;
    }

    public function setExportMode(string $exportName = self::ExportName, bool $exportMode = true): void
    {
        $this->exportMode = $exportMode;
        $this->exportName = $exportName;
    }

    public function processFilterForm(UI\Form $form): void
    {
        if (isset($this->paginator)) {
            $this->page = 1;
        }
        $values = $form->getValues(ArrayHash::class);
        assert($values instanceof ArrayHash);
        $this->appliedFilter = [];
        foreach ($values as $key => $value) {
            if (!self::isEmpty($value)) {
                $column = $this->collection->getColumnByAlphanumericName($key);
                if ($column === null) {
                    throw new InvalidArgument('Column was not found.');
                }
                $this->appliedFilter[$column->getName()] = $value;
            }
        }
        $this->redrawControl('grid');
    }

    private static function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '' || $value === [] || $value === false;
    }

    public function setPagination(int $itemsPerPageOffset = 1): void
    {
        $this->paginator->setVisible();
        $this->paginator->setItemsPerPageOptionOffset($itemsPerPageOffset);
    }

    /**
     * @param int[] $options
     * @param int   $itemsPerPageOffset
     * @return self
     */
    public function setItemsPerPageOptions(array $options, int $itemsPerPageOffset = 1): self
    {
        $this->paginator->setItemsPerPageOptions($options);
        $this->paginator->setItemsPerPageOptionOffset($itemsPerPageOffset);
        return $this;
    }

    /** ******************** Columns ******************** **/

    public function addColumnString(
        string $name,
        ?string $label = null,
        TextAlign $align = TextAlign::Left,
        ?string $unit = null,
        bool $unitFromData = false
    ): ColumnString {
        $label = $label ? Strings::firstUpper(Strings::lower($label)) : Strings::firstUpper(Strings::lower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnString($name, $label, $align, $unit, $unitFromData);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnDate(
        string $name,
        ?string $label = null,
        DateType $dateType = DateType::Date,
        TextAlign $align = TextAlign::Left,
    ): ColumnDate {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnDate($name, $label, $dateType, $align);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnNumber(
        string $name,
        ?string $label = null,
        TextAlign $align = TextAlign::Right,
        ?string $unit = null,
        bool $unitFromData = false
    ): ColumnNumber {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnNumber($name, $label, $align, $unit, $unitFromData);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnBoolean(
        string $name,
        ?string $label = null,
        BooleanType $type = BooleanType::Default,
        TextAlign $align = TextAlign::Left,
    ): ColumnBoolean {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnBoolean($name, $label, $type, $align);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnFunction(
        string $name,
        ?string $label = null,
        TextAlign $align = TextAlign::Left,
    ): ColumnFunction {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        $column = new ColumnFunction($name, $label, $align);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnCheckEmpty(
        string $name,
        ?string $label = null,
        TextAlign $align = TextAlign::Left,
    ): ColumnCheckEmpty {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnCheckEmpty($name, $label, $align);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnImage(
        string $name,
        ?string $label = null,
        ImageType $type = ImageType::Small,
    ): ColumnImage {
        $label = $label ? ucfirst(strtolower($label)) : ucfirst(strtolower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnImage($name, $label, $type);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnFile(
        string $name,
        ?string $label = null,
    ): ColumnFile {
        $label = $label ? Strings::firstUpper(Strings::lower($label)) : Strings::firstUpper(Strings::lower($name));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnFile($name, $label);
        $this->collection->add($column);
        return $column;
    }

    public function addColumnEnum(
        string $name,
        string $label,
        string $enumClass,
        TextAlign $align = TextAlign::Left,
    ): ColumnEnum {
        $label = Strings::firstUpper(Strings::lower($label));
        if ($this->columnSort === null) {
            $this->columnSort = $name;
        }
        $column = new ColumnEnum($name, $label, $enumClass, $align);
        $this->collection->add($column);
        return $column;
    }


    /** ******************** Actions ******************** **/
    public function addAction(ActionType $type, string $link, ?string $title = null, bool $history = false, bool $ajax = true): Action
    {
        $this->actionColumn = true;
        $action = new Action($link, $title, $ajax, $history, $type);
        $this->actions[] = $action;
        return $action;
    }

    public function addActionCallback(ActionType $type, string $primaryKey = 'id'): ActionCallback
    {
        $this->actionColumn = true;
        $action = new ActionCallback($type, $primaryKey);
        $this->actions[] = $action;
        $k = array_key_last($this->actions);
        $this->actions[$k]->setLink($k);
        return $action;
    }

    public function addHeaderAction(ActionType $type, string $link, ?string $title = null, bool $ajax = true, bool $history = true): HeaderAction
    {
        $action = new HeaderAction($link, $title, $ajax, $history, $type);
        $this->headerActions[] = $action;
        return $action;
    }

    /** ******************** Caption ******************** **/
    public function setCaption(string $caption): void
    {
        $this->caption = $caption;
    }

    /** ******************** Filter form ******************** **/

    protected function createComponentFilterForm(): Form
    {
        $form = new Form();
        if ($this->collection->showFilterRow()) {
            foreach ($this->collection as $column) {
                if ($column->isFilterable()) {
                    $hasDefault = array_key_exists($column->getName(), $this->appliedFilter);
                    $default = $hasDefault ? $this->appliedFilter[$column->getName()] : null;

                    $name = $column->getAlphaNumericName();
                    $label = $column->getLabel();

                    if ($column instanceof ColumnEnum) {
                        $options = array_combine(
                            array_column($column->getEnumClass()::cases(), 'value'),
                            array_column($column->getEnumClass()::cases(), 'value'),
                        );
                        $ctrl = $form->addSelect($name, $label, $options)
                            ->setPrompt('Vyberte');
                    } else {
                        $ctrl = $form->addText($name, $label);
                    }

                    if ($hasDefault) {
                        $ctrl->setDefaultValue($default);
                    }
                }
            }
        }
        $form->addSubmit('filter', 'Filtrovat');
        $form->onSubmit[] = [$this, 'processFilterForm'];
        return $form;
    }

    /** ******************** Pagination ******************** **/

    protected function createComponentPaginator(): Paginator
    {
        return $this->paginator;
    }
}
