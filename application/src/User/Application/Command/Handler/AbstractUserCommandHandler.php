<?php

declare(strict_types=1);

namespace XIP\User\Application\Command\Handler;

use XIP\Shared\Domain\Bus\EventBusInterface;
use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

abstract class AbstractUserCommandHandler
{
    protected UserRepositoryInterface $userRepository;
    
    protected EventBusInterface $eventBus;

    public function __construct(UserRepositoryInterface $userRepository, EventBusInterface $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }
}
