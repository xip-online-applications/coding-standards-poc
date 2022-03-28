<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Command;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface CommandHandlerInterface extends MessageSubscriberInterface
{
    public function handle(CommandInterface $command): void;
}
