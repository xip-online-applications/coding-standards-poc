<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository;

use XIP\User\Domain\Model\Role;

interface RoleRepositoryInterface
{
    /**
     * @param array<int, int> $ids
     * @return array<int, Role>
     */
    public function findByIds(array $ids): array;

    /**
     * @return array<int, Role>
     */
    public function findByUserId(int $userId): array;
    
    public function exists(array $ids): bool;
}