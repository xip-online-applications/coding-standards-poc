<?php

declare(strict_types=1);

namespace XIP\User\Application\Command\Handler;

use XIP\Shared\Domain\Bus\EventBusInterface;
use XIP\User\Domain\Repository\UserRepositoryInterface;

abstract class AbstractUserCommandHandler
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected EventBusInterface $eventBus
    ) {
    }
}
