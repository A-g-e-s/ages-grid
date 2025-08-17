<?php

declare(strict_types=1);


namespace Ages\Grid\Column;


use Ages\Grid\TextAlign;
use Ages\Grid\Exception\UnexpectedUse;
use BackedEnum;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;
use UnitEnum;

abstract class Column
{
    const PropertySeparator = '->';
    protected string $alphaNumericName;
    protected string $classes = '';
    protected bool $filterable = false;
    protected bool $sortable = false;
    private bool $ajax = true;
    private string $align;
    private string $label;
    private bool $history = true;
    private bool $checkEmpty = false;
    private ?string $link = null;
    /** @var LinkParam[] */
    private array $linkParam = [];
    private string $template = 'default.latte';
    private string $width = '';

    private bool $manyToMany = false;
    private string|int $manyToManyValue = '';
    private string|int $manyToManyKey = '';
    private string|int $manyToManyName = '';


    public function __construct(
        private readonly string $name,
        ?string $label = null,
        TextAlign $align = TextAlign::Left,
        protected readonly ?string $unit = null,
        private readonly bool $unitFromData = false
    ) {
        $this->label = ($label === null) ? $this->name : $label;
        $this->align = $align->value;
        $this->setAlphaNumericName();
    }

    /** ******************** Setters / Getters ******************** **/

    public function getAlign(): string
    {
        return $this->align;
    }

    public function getClasses(): string
    {
        return sprintf('%s %s %s', $this->classes, $this->align, $this->width);
    }

    public function getAlphaNumericName(): string
    {
        return $this->alphaNumericName;
    }

    public function setAlphaNumericName(?string $suffix = null): void
    {
        $nameTransform = '';
        $properties = explode(self::PropertySeparator, $this->name);
        foreach ($properties as $key => $property) {
            if ($key === array_key_first($properties)) {
                $nameTransform = $property;
            } else {
                $nameTransform .= Strings::firstUpper($property);
            }
        }
        $this->alphaNumericName = ($suffix === null) ? $nameTransform : sprintf('%s_%s', $nameTransform, $suffix);
    }

    public function getColumnValue(IEntity $row): string
    {
        $value = $this->getColumnRawValue($row);
        if ($value instanceof \DateTimeImmutable) {
            if ($this instanceof ColumnDate) {
                $value = $value->format($this->getDateType()->value);
            } else {
                throw new UnexpectedUse(sprintf('Column %s has to be set as ColumnDate', $this->name));
            }
        }
        $unit = $this->getUnit($row);
        if (empty($unit)) {
            return strval($value);
        }
        return sprintf('%s%s', $value, $unit);
    }

    public function getColumnRawValue(IEntity $row): string|int|float|null|bool|\DateTimeImmutable
    {
        return $this->getEntityProperty($row, $this->name);
    }

    public function getEntityProperty(?IEntity $item, string $key, bool $functionParam = false): string|int|float|null|bool|\DateTimeImmutable
    {
        if ($item !== null && $this instanceof ColumnFunction && !$functionParam) {
            $params = [];
            foreach ($this->getFunctionParams() as $functionParam) {
                if ($functionParam->data && is_string($functionParam->value)) {
                    $params[] = $this->getEntityProperty($item, $functionParam->value, true);
                } else {
                    $params[] = $functionParam->value;
                }
            }
            return $item->getRepository()->$key(...$params);
        }

        if ($item === null) {
            return null;
        }
        $properties = explode(self::PropertySeparator, $key);
        $value = $item;
        while ($property = array_shift($properties)) {
            if ($value instanceof OneHasMany) {
                $entity = $value->toCollection()->fetch();
                if ($entity === null) {
                    return null;
                }
                $value = $entity;
            }
            if (!$value->__isset($property)) {
                return null;
            }
            $value = $value->__get($property);
            if ($value instanceof BackedEnum) {
                $value = $value->value;
            } elseif ($value instanceof UnitEnum) {
                $value = $value->name;
            }
            if ($value instanceof ManyHasMany) {
                $entity = $value->toCollection();
                if ($this->manyToMany) {
                    $entity = $entity->findBy([$this->manyToManyKey => $this->manyToManyValue])->fetch();
                    if ($entity === null) {
                        return null;
                    }
                    assert($entity instanceof Entity);
                    if (!$entity->__isset((string)$this->manyToManyName)) {
                        return null;
                    }
                    $entity = $entity->__get((string)$this->manyToManyName);
                }
                $value = $entity;
            }
        }
        return $value;
    }

    public function getUnit(IEntity $row): null|string
    {
        if ($this->unit === null) {
            return null;
        }
        if ($this->unitFromData) {
            $property = $this->getEntityProperty($row, $this->unit);
            if ($property instanceof \DateTimeImmutable) {
                return null;
            }
            return strval($property);
        }
        return $this->unit;
    }

    public function getWidth(): string
    {
        return $this->width;
    }

    public function setWidth(WidthClasses $width): static
    {
        $this->width = $width->value;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLink(): null|string
    {
        return $this->link;
    }

    public function setLink(string $link, bool $ajax = true, bool $history = true, bool $checkEmpty = false): static
    {
        $this->ajax = $ajax;
        $this->link = $link;
        $this->history = $history;
        $this->checkEmpty = $checkEmpty;
        return $this;
    }

    public function setManyToMany(string|int $key, string|int $value, string|int $name): static
    {
        $this->manyToMany = true;
        $this->manyToManyKey = $key;
        $this->manyToManyValue = $value;
        $this->manyToManyName = $name;
        return $this;
    }

    /** @return LinkParam[] */
    public function getLinkParams(): array
    {
        return $this->linkParam;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    protected function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function hasUnit(): bool
    {
        return !(($this->unit === null));
    }

    public function sortAndFilter(): static
    {
        $this->setSortable();
        return $this->setFilterable();
    }

    /**
     * @param string|array<int, string> $class
     * @return static
     */
    public function addClass(string|array $class): static
    {
        if (is_string($class)) {
            $this->classes .= sprintf(' %s', $class);
        } else {
            foreach ($class as $value) {
                $this->classes .= sprintf(' %s', $value);
            }
        }
        return $this;
    }

    public function addLinkParam(string $key, string $value, bool $data = true): static
    {
        $this->linkParam[] = new LinkParam($key, $value, $data);;
        return $this;
    }

    /** ******************** Other methods ******************** **/

    public function ajax(): bool
    {
        return $this->ajax;
    }

    public function history(): bool
    {
        return $this->history;
    }

    public function checkEmpty(): bool
    {
        return $this->checkEmpty;
    }

    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    public function setFilterable(): static
    {
        $this->filterable = true;
        return $this;
    }

    public function isLink(): bool
    {
        return !(($this->link === null));
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function setSortable(): static
    {
        $this->sortable = true;
        return $this;
    }

    public function unitFromData(): bool
    {
        return $this->unitFromData;
    }
}
