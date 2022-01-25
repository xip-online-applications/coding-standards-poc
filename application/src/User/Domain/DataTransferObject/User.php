<?php

declare(strict_types=1);

namespace App\User\Domain\DataTransferObject;

final class User
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly ?string $password,
    )
    {
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
}