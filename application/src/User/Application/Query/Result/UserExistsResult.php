<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Result;

use XIP\Shared\Domain\Query\QueryResultInterface;

class UserExistsResult implements QueryResultInterface
{
    public function __construct(
        private bool $exists
    ) {
    }

    public function exists(): bool
    {
        return $this->exists;
    }
}
