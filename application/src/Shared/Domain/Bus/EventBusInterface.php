<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Bus;

use XIP\Shared\Domain\Event\EventInterface;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
