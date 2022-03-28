<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Query;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface QueryHandlerInterface extends MessageSubscriberInterface
{
    public function handle(QueryInterface $query): QueryResultInterface;
}
