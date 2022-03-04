<?php

declare(strict_types=1);

namespace XIP\User\Domain\DataTransferObject;

final class User
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly ?string $password,
        private readonly array $roleIds,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array<int, int>
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
