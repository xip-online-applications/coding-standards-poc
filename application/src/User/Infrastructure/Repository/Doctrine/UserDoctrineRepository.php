<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use XIP\Shared\Domain\Exception\ModelNotFoundException;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\Role;
use XIP\User\Domain\Model\User;
use XIP\User\Domain\Repository\UserRepositoryInterface;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role as RoleEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository as RoleEntityRepository;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository as UserEntityRepository;

class UserDoctrineRepository implements UserRepositoryInterface
{
    private UserEntityRepository $userEntityRepository;
    
    private RoleEntityRepository $roleEntityRepository;
    
    private ?EntityManagerInterface $entityManager = null;

    public function __construct(UserEntityRepository $userEntityRepository, RoleEntityRepository $roleEntityRepository)
    {
        $this->userEntityRepository = $userEntityRepository;
        $this->roleEntityRepository = $roleEntityRepository;
    }

    public function findAll(): array
    {
        $userEntities = $this->userEntityRepository->createQueryBuilder('user')
            ->indexBy('user', 'user.id')
            ->getQuery()
            ->execute();

        return $this->hydrateAll($userEntities);
    }

    public function findByIds(array $ids): array
    {
        $userEntities = $this->userEntityRepository->createQueryBuilder('user')
            ->indexBy('user', 'user.id')
            ->where('user.id in :ids')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();

        return $this->hydrateAll($userEntities);
    }

    public function findById(int $id): ?User
    {
        $userEntity = $this->userEntityRepository->findOneBy(['id' => $id]);

        if (null === $userEntity) {
            return null;
        }

        return $this->hydrate($userEntity);
    }

    public function findOrFailById(int $id): User
    {
        $user = $this->findById($id);

        if (null === $user) {
            throw new ModelNotFoundException(
                sprintf('User with id %d was not found in doctrine', $id)
            );
        }

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $userEntity = $this->userEntityRepository->findOneBy(['email' => $email]);

        if (null === $userEntity) {
            return null;
        }

        return $this->hydrate($userEntity);
    }

    public function findOrFailByEmail(string $email): User
    {
        $user = $this->findByEmail($email);

        if (null === $user) {
            throw new ModelNotFoundException(
                sprintf('User with email %s was not found in doctrine', $email)
            );
        }

        return $user;
    }

    public function exists(string $email): bool
    {
        $user = $this->findByEmail($email);

        return null !== $user;
    }

    public function unique(string $email): bool
    {
        $user = $this->findByEmail($email);

        return null === $user;
    }

    public function store(UserDto $userDto): User
    {
        $userEntity = $this->userEntityRepository->create();

        $this->flushEntity($userDto, $userEntity);

        return $this->findOrFailById($userEntity->getId());
    }

    public function update(UserDto $userDto, User $user): User
    {
        $userEntity = $this->userEntityRepository->findOneBy(['id' => $user->getId()]);

        $this->flushEntity($userDto, $userEntity);

        return $this->findOrFailById($user->getId());
    }

    public function delete(User $user): void
    {
        $userEntity = $this->userEntityRepository->findOneBy(['id' => $user->getId()]);
        
        $this->getEntityManager()->remove($userEntity);
        $this->getEntityManager()->flush();
    }

    private function flushEntity(UserDto $userDto, UserEntity $userEntity): void
    {
        $now = new \DateTimeImmutable();

        $userEntity->setName($userDto->getName());
        $userEntity->setEmail($userDto->getEmail());
        $userEntity->setPassword($userDto->getPassword());

        $userEntity->setRoles(
            new ArrayCollection(
                $this->roleEntityRepository->findBy(['id' => $userDto->getRoleIds()])
            )
        );

        if (!$this->getEntityManager()->getUnitOfWork()->isInIdentityMap($userEntity)) {
            $userEntity->setCreatedAt($now);
        }

        $userEntity->setUpdatedAt($now);

        $this->getEntityManager()->persist($userEntity);
        $this->getEntityManager()->flush();
    }
    
    private function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->userEntityRepository->createQueryBuilder('user')->getEntityManager();
        }
        
        return $this->entityManager;
    }
    

    private function hydrate(UserEntity $userEntity): User
    {
        return new User(
            $userEntity->getId(),
            $userEntity->getName(),
            $userEntity->getEmail(),
            $userEntity->getPassword(),
            $this->hydrateAllRoles($userEntity->getRoles())
        );
    }

    /**
     * @param UserEntity[] $userEntities
     * @return User[]
     */
    private function hydrateAll(array $userEntities): array
    {
        return array_map([$this, 'hydrate'], $userEntities);
    }

    /**
     * @param Collection<int, RoleEntity> $rolesCollection
     * @return array<int, Role>
     */
    private function hydrateAllRoles(Collection $rolesCollection): array
    {
        $roles = [];

        foreach ($rolesCollection as $role) {
            $roles[$role->getId()] = $this->hydrateRole($role);
        }

        return $roles;
    }

    private function hydrateRole(RoleEntity $roleEntity): Role
    {
        return new Role(
            $roleEntity->getId(),
            $roleEntity->getName()
        );
    }
}
