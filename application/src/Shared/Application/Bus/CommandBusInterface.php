<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use XIP\Shared\Application\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
