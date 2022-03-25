<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Application\Query\QueryResultInterface;
use XIP\User\Domain\Model\User;

class UserResult implements QueryResultInterface
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
