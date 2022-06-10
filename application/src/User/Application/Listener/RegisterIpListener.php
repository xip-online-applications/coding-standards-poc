<?php

declare(strict_types=1);

namespace XIP\User\Application\Listener;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Domain\Event\EventInterface;
use XIP\Shared\Domain\Event\ListenerInterface;
use XIP\User\Application\Event\UserStoredEvent;

class RegisterIpListener implements ListenerInterface
{
    public function handle(EventInterface $event): void
    {
        if (!$event instanceof UserStoredEvent) {
            throw new UnexpectedTypeException($event, UserStoredEvent::class);
        }
    }

    public static function getHandledMessages(): iterable
    {
        yield UserStoredEvent::class => [
            'method' => 'handle',
            'from_transport' => 'events_transport',
            'bus' => 'events.bus',
        ];
    }
}