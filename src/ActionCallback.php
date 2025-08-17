<?php

declare(strict_types=1);


namespace Ages\Grid;

use Closure;
use Nette\SmartObject;

/**
 * @method void onCall(int $primaryKey)
 */
class ActionCallback extends Action
{
    use SmartObject;

    /** @var array<int, Closure> */
    public array $onCall = [];

    public function __construct(
        ActionType $type,
        string $primaryKey
    ) {
        parent::__construct(0, null, true, false, $type);
        $this->addLinkParam('primaryId', $primaryKey);
    }

    public function callback(int $primaryKey): void
    {
        foreach ($this->onCall as $handler) {
            $handler($primaryKey);
        }
    }


}
