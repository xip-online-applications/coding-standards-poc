<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use XIP\User\Domain\Repository\UserRepositoryInterface;

abstract class AbstractUserQueryHandler
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
