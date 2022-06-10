<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Domain\Query\QueryInterface;

class FindUserQuery implements QueryInterface
{

    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
