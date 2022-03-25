<?php

declare(strict_types=1);

namespace XIP\User\Application\Event;

use XIP\Shared\Application\Event\EventInterface;
use XIP\User\Domain\Model\User;

class UserStoredEvent implements EventInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
