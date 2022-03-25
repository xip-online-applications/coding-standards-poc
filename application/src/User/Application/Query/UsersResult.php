<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Application\Query\QueryResultInterface;
use XIP\User\Domain\Model\User;

class UsersResult implements QueryResultInterface
{
    private array $users;

    /**
     * @param User[] $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }
}
