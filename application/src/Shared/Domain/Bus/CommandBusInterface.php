<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Bus;

use XIP\Shared\Domain\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
