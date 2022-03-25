<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use XIP\Shared\Application\Command\CommandInterface;

class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $messageCommandBus;

    public function __construct(MessageBusInterface $messageCommandBus)
    {
        $this->messageCommandBus = $messageCommandBus;
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->messageCommandBus->dispatch($command);
    }
}
