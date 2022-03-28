<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Event;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface ListenerInterface extends MessageSubscriberInterface
{
    public function handle(EventInterface $event): void;
}
