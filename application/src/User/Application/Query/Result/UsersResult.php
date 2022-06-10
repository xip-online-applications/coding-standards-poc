<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Result;

use XIP\Shared\Domain\Query\QueryResultInterface;
use XIP\User\Domain\Model\User;

class UsersResult implements QueryResultInterface
{
    /**
     * @param User[] $users
     */
    public function __construct(
        private array $users
    ) {
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
