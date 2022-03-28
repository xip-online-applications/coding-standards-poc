<?php

declare(strict_types=1);

namespace XIP\User\Application\Command;

use XIP\Shared\Application\Command\CommandInterface;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\User;

class UpdateUserCommand implements CommandInterface
{
    private UserDto $userDto;
    private User $user;

    public function __construct(UserDto $userDto, User $user)
    {
        $this->userDto = $userDto;
        $this->user = $user;
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
