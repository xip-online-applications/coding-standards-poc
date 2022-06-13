<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use XIP\Shared\Domain\Bus\EventBusInterface;
use XIP\Shared\Domain\Event\EventInterface;

class EventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $messageEventBus
    ) {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->messageEventBus->dispatch($event);
    }
}
