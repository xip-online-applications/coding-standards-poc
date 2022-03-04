<?php

declare(strict_types=1);

namespace XIP\User\Domain\DataTransferObject;

final class Role
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}