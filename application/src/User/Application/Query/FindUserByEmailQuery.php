<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Domain\Query\QueryInterface;

class FindUserByEmailQuery implements QueryInterface
{
    public function __construct(
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
