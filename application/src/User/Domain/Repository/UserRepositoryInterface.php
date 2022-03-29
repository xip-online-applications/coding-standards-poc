<?php

declare(strict_types=1);

namespace XIP\User\Domain\Repository;

use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\User;

interface UserRepositoryInterface
{
    /**
     * @return array<int, User>
     */
    public function findAll(): array;

    /**
     * @param array<int, int> $ids
     * @return array<int, User>
     */
    public function findByIds(array $ids): array;
    
    public function findById(int $id): ?User;
    
    public function findOrFailById(int $id): User;
    
    public function findByEmail(string $email): ?User;
    
    public function findOrFailByEmail(string $email): User;
    
    public function exists(string $email): bool;
    
    public function unique(string $email): bool;
    
    public function store(UserDto $userDto): User;
    
    public function update(UserDto $userDto, User $user): User;
    
    public function delete(User $user): void;
}
