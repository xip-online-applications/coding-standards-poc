<?php

declare(strict_types=1);

namespace XIP\User\Application\Command\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Command\CommandHandlerInterface;
use XIP\Shared\Application\Command\CommandInterface;
use XIP\User\Application\Command\StoreUserCommand;
use XIP\User\Application\Event\UserStoredEvent;

class StoreUserCommandHandler extends AbstractUserCommandHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): void
    {
        if (!$command instanceof StoreUserCommand) {
            throw new UnexpectedTypeException($command, StoreUserCommand::class);
        }

        $user = $this->userRepository->store($command->getUserDto());
        
        $this->eventBus->dispatch(new UserStoredEvent($user));
    }

    public static function getHandledMessages(): iterable
    {
        yield StoreUserCommand::class => [
            'method' => 'handle',
            'from_transport' => 'commands_transport',
            'bus' => 'commands.bus',
        ];
    }
}
