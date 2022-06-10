<?php

declare(strict_types=1);

namespace XIP\User\Application\Event;

use XIP\Shared\Domain\Event\EventInterface;
use XIP\User\Domain\Model\User;

class UserUpdatedEvent implements EventInterface
{
    public function __construct(
        private User $oldUser,
        private User $updatedUser
    ) {
    }

    public function getOldUser(): User
    {
        return $this->oldUser;
    }

    public function getUpdatedUser(): User
    {
        return $this->updatedUser;
    }
}
