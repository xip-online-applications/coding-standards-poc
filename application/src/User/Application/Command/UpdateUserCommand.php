<?php

declare(strict_types=1);

namespace XIP\User\Application\Command;

use XIP\Shared\Domain\Command\CommandInterface;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\User;

class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        private UserDto $userDto,
        private User $user
    ) {
    }

    public function getUserDto(): UserDto
    {
        return $this->userDto;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
