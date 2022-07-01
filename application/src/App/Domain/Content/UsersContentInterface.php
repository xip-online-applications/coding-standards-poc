<?php

declare(strict_types=1);

namespace XIP\App\Domain\Content;

use XIP\User\Domain\Model\User;

interface UsersContentInterface
{
    /**
     * @param User[] $users
     */
    public function compose(array $users): string;
}
