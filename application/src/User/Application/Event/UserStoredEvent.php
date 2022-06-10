<?php

declare(strict_types=1);

namespace XIP\User\Application\Event;

use XIP\Shared\Domain\Event\EventInterface;
use XIP\User\Domain\Model\User;

class UserStoredEvent implements EventInterface
{
    public function __construct(
        private User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
