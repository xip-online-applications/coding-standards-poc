<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Query\Result;

use XIP\Shared\Domain\Query\QueryResultInterface;

class LastUpdatedAtResult implements QueryResultInterface
{
    public function __construct(
        private \DateTimeInterface $lastUpdatedAt
    ) {
    }

    public function getLastUpdatedAt(): \DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }
}