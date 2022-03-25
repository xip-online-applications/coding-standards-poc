<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Application\Query\QueryInterface;

class FindUserByEmailQuery implements QueryInterface
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
