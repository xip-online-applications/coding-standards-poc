<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Result;

use XIP\Shared\Domain\Query\QueryResultInterface;
use XIP\User\Domain\Model\User;

class UserResult implements QueryResultInterface
{
    public function __construct(
        private User $user
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
