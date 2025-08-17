<?php

declare(strict_types=1);


namespace Ages\Grid;


use Ages\Grid\Column\LinkParam;
use Ages\Grid\Exception\InvalidArgument;

class HeaderAction
{
    /** @var LinkParam[] */
    private array $linkParams = [];

    public function __construct(
        private readonly string $link,
        private readonly ?string $title,
        private readonly bool $ajax,
        private readonly bool $history,
        private readonly ActionType $type
    ) {
        if ($this->type === ActionType::Link && $this->title === null) {
            throw new InvalidArgument('Please set title for Link type');
        }
    }

    public function addLinkParam(string $key, string|int $value): static
    {
        $this->linkParams[] = new LinkParam($key, $value, false);;
        return $this;
    }

    /**
     * @return LinkParam[]
     */
    public function getLinkParams(): array
    {
        return $this->linkParams;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): ActionType
    {
        return $this->type;
    }

    public function isAjax(): bool
    {
        return $this->ajax;
    }

    public function isHistory(): bool
    {
        return $this->history;
    }


}
