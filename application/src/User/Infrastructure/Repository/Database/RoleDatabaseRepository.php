<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

use Doctrine\DBAL\Query\QueryBuilder;
use XIP\Shared\Infrastructure\Repository\AbstractDatabaseRepository;
use XIP\User\Domain\Model\Role;
use XIP\User\Infrastructure\Repository\RoleRepositoryInterface;

class RoleDatabaseRepository extends AbstractDatabaseRepository implements RoleRepositoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function findByUserId(int $userId): array
    {
        $roleInfos = $this->createSelect()
            ->innerJoin(
                RoleTable::NAME,
                UserRolePivot::NAME,
                UserRolePivot::NAME,
                $this->onUserIdEqualsRoleUserUserId()
            )
            ->where($this->whereUserId())
            ->setParameter('userId', $userId)
            ->executeQuery()
            ->fetchAllAssociative();
        
        return $this->hydrateAll($roleInfos);
    }

    public function exists(array $ids): bool
    {
        $count = $this->createSelect([sprintf('count(%s.%s)', RoleTable::NAME, RoleTable::COLUMN_ID)])
            ->where($this->whereIdInIds())
            ->setParameter('ids', implode(', ', $ids))
            ->executeQuery()
            ->fetchOne();
        
        return count($ids) === $count;
    }

    private function createSelect(array $select = RoleTable::SELECT): QueryBuilder
    {
        return $this->createQueryBuilder()->select($select)->from(RoleTable::NAME);
    }

    private function whereUserId(): string
    {
        return sprintf('%s.%s = :userId', UserRolePivot::NAME, UserRolePivot::COLUMN_USER_ID);
    }

    private function whereIdInIds(): string
    {
        return sprintf('%s.%s in (:ids)', RoleTable::NAME, RoleTable::COLUMN_ID);
    }

    private function onUserIdEqualsRoleUserUserId(): string
    {
        return sprintf('%s.%s = %s.%s', RoleTable::NAME, RoleTable::COLUMN_ID, UserRolePivot::NAME, UserRolePivot::COLUMN_ROLE_ID);
    }

    /**
     * @param array<string, (int|string)> $roleInfo
     */
    private function hydrate(array $roleInfo): Role
    {
        return new Role(
            (int)$roleInfo[RoleTable::COLUMN_ID],
            (string)$roleInfo[RoleTable::COLUMN_NAME],
        );
    }

    /**
     * @param array<int, array<string, (int|string)>> $roleInfos
     * @return Role[]
     */
    private function hydrateAll(array $roleInfos): array
    {
        $roleInfos = $this->keyBy(RoleTable::COLUMN_ID, $roleInfos);
        
        return array_map([$this, 'hydrate'], $roleInfos);
    }
}