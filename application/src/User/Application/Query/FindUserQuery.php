<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use XIP\Shared\Domain\Query\QueryInterface;

class FindUserQuery implements QueryInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
