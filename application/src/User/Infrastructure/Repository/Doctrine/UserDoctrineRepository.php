<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Doctrine;

use XIP\Shared\Domain\Exception\ModelNotFoundException;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\User;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository;
use XIP\User\Infrastructure\Repository\RoleRepositoryInterface;
use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

class UserDoctrineRepository implements UserRepositoryInterface
{
    private UserRepository $userRepository;
    private RoleRepositoryInterface $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function findAll(): array
    {
        $userEntities = $this->userRepository->findAll();

        return $this->hydrateAll($userEntities);
    }

    public function findByIds(array $ids): array
    {
        // TODO: Implement findByIds() method.
    }

    public function findById(int $id): ?User
    {
        $userEntity = $this->userRepository->findOneBy(['id' => $id]);

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
        $userEntity = $this->userRepository->findOneBy(['email' => $email]);

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
        $user = new UserEntity;
        $user->setName($userDto->getName());
        $user->setEmail($userDto->getEmail());
        $user->setPassword(null);
    }

    public function update(UserDto $userDto, User $user): User
    {
        // TODO: Implement update() method.
    }

    public function delete(User $user): void
    {
        $this->userRepository->createQueryBuilder('user')
            ->delete()
            ->where('id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->execute();
    }

    private function hydrate(UserEntity $userEntity): User
    {
        return new User(
            $userEntity->getId(),
            $userEntity->getName(),
            $userEntity->getEmail(),
            $userEntity->getPassword(),
            $this->roleRepository->findByUserId($userEntity->getId())
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
}
