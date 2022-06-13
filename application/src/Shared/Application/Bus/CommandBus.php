<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use XIP\Shared\Domain\Bus\CommandBusInterface;
use XIP\Shared\Domain\Command\CommandInterface;

class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $messageCommandBus
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->messageCommandBus->dispatch($command);
    }
}
