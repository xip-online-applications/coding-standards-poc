<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine;

use XIP\User\Domain\Model\Role;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role as RoleEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository;
use XIP\User\Infrastructure\Repository\RoleRepositoryInterface;

class RoleDoctrineRepository implements RoleRepositoryInterface
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function findByIds(array $ids): array
    {
        $roles = $this->roleRepository->createQueryBuilder('role')
            ->indexBy('role', 'id')
            ->where('role.id = :suerId')
            ->setParameter('id', $ids)
            ->getQuery()
            ->execute();

        return $this->hydrateAll($roles);
    }

    /**
     * {@inheritDoc}
     */
    public function findByUserId(int $userId): array
    {
        $roles = $this->roleRepository->createQueryBuilder('role')
            ->indexBy('role', 'id')
            ->innerJoin(UserEntity::class,  'user')
            ->where('user.id = :suerId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();

        return $this->hydrateAll($roles);
    }

    public function exists(array $ids): bool
    {
        $count = $this->roleRepository->createQueryBuilder('role')
            ->select('count(role.id)')
            ->where('role.id IN :ids')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();

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