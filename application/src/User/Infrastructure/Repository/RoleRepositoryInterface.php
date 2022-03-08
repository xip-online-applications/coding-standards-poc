<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository;

use XIP\User\Domain\Model\Role;

interface RoleRepositoryInterface
{
    /**
     * @return array<int, Role>
     */
    public function findByUserId(int $userId): array;
    
    public function exists(array $ids): bool;
}