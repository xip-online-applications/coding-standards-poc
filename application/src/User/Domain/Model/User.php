<?php

declare(strict_types=1);

namespace XIP\User\Domain\Model;

use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly ?string $password,
        private readonly array $roles,
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array<int, Role>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        throw new \RuntimeException('Credentials can only be erased through the oAuth provider.');
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
