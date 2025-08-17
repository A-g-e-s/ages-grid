<?php

declare(strict_types=1);


namespace Ages\Grid;


use Ages\Grid\Column\LinkParam;

class Action
{
    /** @var LinkParam[] */
    private array $linkParams = [];

    public function __construct(
        private string|int $link,
        private readonly ?string $title,
        private readonly bool $ajax,
        private readonly bool $history,
        private readonly ActionType $type
    ) {
    }

    public function addLinkParam(string $key, bool|string $value, bool $data = true): static
    {
        $this->linkParams[] = new LinkParam($key, $value, $data);;
        return $this;
    }

    public function getLink(): string|int
    {
        return $this->link;
    }

    public function setLink(string|int $key): void
    {
        $this->link = $key;
    }

    /**
     * @return LinkParam[]
     */
    public function getLinkParams(): array
    {
        return $this->linkParams;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function isAjax(): bool
    {
        return $this->ajax;
    }

    public function isHistory(): bool
    {
        return $this->history;
    }

    public function getType(): ActionType
    {
        return $this->type;
    }


}
