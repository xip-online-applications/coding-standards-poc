<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

abstract class AbstractUserQueryHandler
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
