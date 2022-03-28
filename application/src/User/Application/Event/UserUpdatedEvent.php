<?php

declare(strict_types=1);

namespace XIP\User\Application\Event;

use XIP\Shared\Domain\Event\EventInterface;
use XIP\User\Domain\Model\User;

class UserUpdatedEvent implements EventInterface
{
    private User $oldUser;
    
    private User $updatedUser;

    public function __construct(User $oldUser, User $updatedUser)
    {
        $this->oldUser = $oldUser;
        $this->updatedUser = $updatedUser;
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
