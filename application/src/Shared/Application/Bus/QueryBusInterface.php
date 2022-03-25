<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): QueryResultInterface;
}
