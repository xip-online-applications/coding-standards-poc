<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use XIP\Shared\Domain\Bus\QueryBusInterface;
use XIP\Shared\Domain\Query\QueryInterface;
use XIP\Shared\Domain\Query\QueryResultInterface;

class QueryBus implements QueryBusInterface
{
    public function __construct(
        private MessageBusInterface $messageQueryBus
    ) {
    }

    public function query(QueryInterface $query): QueryResultInterface
    {
        $envelope = $this->messageQueryBus->dispatch($query);
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \RuntimeException(sprintf('%s failed to execute.', get_class($query)));
        }

        return $stamp->getResult();
    }
}
