<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine;

use XIP\User\Domain\Model\Role;
use XIP\User\Domain\Repository\RoleRepositoryInterface;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role as RoleEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository as RoleEntityRepository;

class RoleDoctrineRepository implements RoleRepositoryInterface
{
    public function __construct(private RoleEntityRepository $roleEntityRepository)
    {
    }
    
    /**
     * {@inheritDoc}
     */
    public function findByUserId(int $userId): array
    {
        $roles = $this->roleEntityRepository->createQueryBuilder('role')
            ->indexBy('role', 'role.id')
            ->innerJoin('role.users', 'user')
            ->where('user.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();

        return $this->hydrateAll($roles);
    }

    public function exists(array $ids): bool
    {
        $count = $this->roleEntityRepository->createQueryBuilder('role')
            ->select('count(role.id)')
            ->where('role.id in (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getSingleScalarResult();
        
        return $count === count($ids);
    }

    private function hydrate(RoleEntity $roleEntity): Role
    {
        return new Role(
            $roleEntity->getId(),
            $roleEntity->getName()
        );
    }

    /**
     * @param array<int, RoleEntity> $roleEntities
     * @return array<int, Role>
     */
    private function hydrateAll(array $roleEntities): array
    {
        return array_map([$this, 'hydrate'], $roleEntities);
    }
}
