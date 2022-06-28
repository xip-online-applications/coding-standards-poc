<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Domain\Query\QueryInterface;

class LastUpdatedAtForUserQuery implements QueryInterface
{
    public function __construct(
        private int $userId
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}