<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;

class QueryBus implements QueryBusInterface
{
    private MessageBusInterface $messageQueryBus;

    public function __construct(MessageBusInterface $messageQueryBus)
    {
        $this->messageQueryBus = $messageQueryBus;
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
