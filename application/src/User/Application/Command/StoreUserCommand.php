<?php

declare(strict_types=1);

namespace XIP\User\Application\Command;

use XIP\Shared\Application\Command\CommandInterface;
use XIP\User\Domain\DataTransferObject\User as UserDto;

class StoreUserCommand implements CommandInterface
{
    private UserDto $userDto;

    public function __construct(UserDto $userDto)
    {
        $this->userDto = $userDto;
    }

    public function getUserDto(): UserDto
    {
        return $this->userDto;
    }
}
