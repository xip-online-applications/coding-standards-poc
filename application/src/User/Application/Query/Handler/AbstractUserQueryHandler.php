<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use XIP\User\Domain\Repository\UserRepositoryInterface;

abstract class AbstractUserQueryHandler
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }
}
