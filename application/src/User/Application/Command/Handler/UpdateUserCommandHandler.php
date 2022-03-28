<?php

declare(strict_types=1);

namespace XIP\User\Application\Command\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Command\CommandHandlerInterface;
use XIP\Shared\Application\Command\CommandInterface;
use XIP\User\Application\Command\UpdateUserCommand;
use XIP\User\Application\Event\UserUpdatedEvent;

class UpdateUserCommandHandler extends AbstractUserCommandHandler implements CommandHandlerInterface
{
    public function handle(CommandInterface $command): void
    {
        if (!$command instanceof UpdateUserCommand) {
            throw new UnexpectedTypeException($command, UpdateUserCommand::class);
        }
        
        $updatedUser = $this->userRepository->update($command->getUserDto(), $command->getUser());
        
        $this->eventBus->dispatch(new UserUpdatedEvent($command->getUser(), $updatedUser));
    }

    public static function getHandledMessages(): iterable
    {
        yield UpdateUserCommand::class => [
            'method' => 'handle',
            'from_transport' => 'commands_transport',
            'bus' => 'commands.bus',
        ];
    }
}
