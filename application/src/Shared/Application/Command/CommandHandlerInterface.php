<?php

declare(strict_types=1);

namespace XIP\Shared\Application\Command;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface CommandHandlerInterface extends MessageSubscriberInterface
{
    public function handle(CommandInterface $command): void;
}
