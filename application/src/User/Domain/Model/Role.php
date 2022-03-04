<?php

declare(strict_types=1);

namespace XIP\User\Domain\Model;

final class Role
{
    public function __construct(
        private readonly int $id,
        private readonly string $name
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
