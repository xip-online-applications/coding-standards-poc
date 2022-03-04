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
    public function findByIds(array $ids): array
    {
        $roleInfos = $this->createSelect()
            ->where($this->whereIdInIds())
            ->setParameter('ids', $ids)
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->hydrateAll($roleInfos);
    }
    
    /**
     * {@inheritDoc}
     */
    public function findByUserId(int $userId): array
    {
        $roleInfos = $this->createSelect()
            ->innerJoin(
                UserTable::NAME,
                RoleUserPivot::NAME,
                RoleUserPivot::NAME,
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
            ->setParameter('ids', $ids)
            ->executeQuery()
            ->fetchNumeric();

        return count($ids) === $count;
    }

    private function createSelect(array $select = RoleTable::SELECT): QueryBuilder
    {
        return $this->createQueryBuilder()->select($select)->from(RoleTable::NAME);
    }

    private function whereUserId(): string
    {
        return sprintf('%s.%s = :userId', RoleUserPivot::NAME, RoleUserPivot::COLUMN_USER_ID);
    }

    private function whereIdInIds(): string
    {
        return sprintf('%s.%s IN (:ids)', RoleTable::NAME, RoleTable::COLUMN_ID);
    }

    private function onUserIdEqualsRoleUserUserId(): string
    {
        return sprintf('%s.%s = %s.%s', UserTable::NAME, UserTable::COLUMN_ID, RoleUserPivot::NAME, RoleUserPivot::COLUMN_ROLE_ID);
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
     * @param array<int, array<string, (int|string)>> $userInfos
     * @return Role[]
     */
    private function hydrateAll(array $roleInfos): array
    {
        return $this->keyBy(
            RoleTable::COLUMN_ID,
            array_map([$this, 'hydrate'], $roleInfos)
        );
    }
}