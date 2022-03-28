<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Bus;

use XIP\Shared\Domain\Query\QueryInterface;
use XIP\Shared\Domain\Query\QueryResultInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): QueryResultInterface;
}
