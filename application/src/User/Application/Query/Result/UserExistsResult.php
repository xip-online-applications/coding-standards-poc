<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Result;

use XIP\Shared\Application\Query\QueryResultInterface;

class UserExistsResult implements QueryResultInterface
{
    private bool $exists;

    public function __construct(bool $exists)
    {
        $this->exists = $exists;
    }

    public function exists(): bool
    {
        return $this->exists;
    }
}
