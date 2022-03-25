<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use XIP\Shared\Application\Event\EventInterface;

class EventBus implements EventBusInterface
{
    private MessageBusInterface $messageEventBus;

    public function __construct(MessageBusInterface $messageEventBus)
    {
        $this->messageEventBus = $messageEventBus;
    }

    public function dispatch(EventInterface $event): void
    {
        $this->messageEventBus->dispatch($event);
    }
}
