<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use XIP\Shared\Application\Event\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
