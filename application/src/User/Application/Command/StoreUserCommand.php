<?php

declare(strict_types=1);

namespace XIP\User\Application\Command;

use XIP\Shared\Domain\Command\CommandInterface;
use XIP\User\Domain\DataTransferObject\User as UserDto;

class StoreUserCommand implements CommandInterface
{

    public function __construct(
        private UserDto $userDto
    ) {
    }

    public function getUserDto(): UserDto
    {
        return $this->userDto;
    }
}
