<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use XIP\Shared\Domain\Exception\ModelNotFoundException;
use XIP\Shared\Infrastructure\Repository\AbstractDatabaseRepository;
use XIP\User\Domain\DataTransferObject\User as UserDTO;
use XIP\User\Domain\Model\Role;
use XIP\User\Domain\Model\User;
use XIP\User\Domain\Repository\RoleRepositoryInterface;
use XIP\User\Domain\Repository\UserRepositoryInterface;

class UserDatabaseRepository extends AbstractDatabaseRepository implements UserRepositoryInterface
{
    private RoleRepositoryInterface $roleRepository;
    
    private array $users = [];

    public function __construct(
        Connection $connection,
        string $databaseDateTimeFormat,
        RoleRepositoryInterface $roleRepository
    ) {
        parent::__construct($connection, $databaseDateTimeFormat);

        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(): array
    {
        $userInfos = $this->createSelect()
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->hydrateAll($userInfos);
    }

    /**
     * {@inheritDoc}
     */
    public function findByIds(array $ids): array
    {
        $userInfos = $this->createSelect()
            ->where($this->whereIdInIds())
            ->setParameter('ids', $ids)
            ->executeQuery()
            ->fetchAllAssociative();

        return $this->hydrateAll($userInfos);
    }

    public function findById(int $id): ?User
    {
        if (array_key_exists($id, $this->users)) {
            return $this->users[$id];
        }
        
        $userInfo = $this->createSelect()
            ->where($this->whereIdEquals())
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();
        
        if (false === $userInfo) {
            return null;
        }

        $this->users[$id] = $this->hydrate($userInfo);
        
        return $this->users[$id];
    }

    public function findOrFailById(int $id): User
    {
        $user = $this->findById($id);

        if (null === $user) {
            throw new ModelNotFoundException(
                sprintf('User with id %d was not found in the database', $id)
            );
        }

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $userInfo = $this->createSelect()
            ->where($this->whereEmailEquals())
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAssociative();

        if (false === $userInfo) {
            return null;
        }

        return $this->hydrate($userInfo);
    }

    public function findOrFailByEmail(string $email): User
    {
         $user = $this->findByEmail($email);

         if (null === $user) {
             throw new ModelNotFoundException(
                 sprintf('User with email %s was not found in the database', $email)
             );
         }

         return $user;
    }

    public function exists(string $email): bool
    {
        $count = $this->createSelect([sprintf('count(%s.%s)', UserTable::NAME, UserTable::COLUMN_ID)])
            ->where($this->whereEmailEquals())
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchOne();

        return 1 === $count;
    }

    public function unique(string $email): bool
    {
        return !$this->exists($email);
    }

    public function store(UserDTO $userDto): User
    {
        $now = (new \DateTimeImmutable());

        $this->createQueryBuilder()
            ->insert(UserTable::NAME)
            ->values([
                UserTable::COLUMN_NAME => ':name',
                UserTable::COLUMN_EMAIL => ':email',
                UserTable::COLUMN_PASSWORD => ':password',
                UserTable::COLUMN_CREATED_AT => ':createdAt',
                UserTable::COLUMN_UPDATED_AT => ':updatedAt',
            ])
            ->setParameter('name', $userDto->getName())
            ->setParameter('email', $userDto->getEmail())
            ->setParameter('password', $userDto->getPassword())
            ->setParameter('createdAt', $now->format($this->databaseDateTimeFormat))
            ->setParameter('updatedAt', $now->format($this->databaseDateTimeFormat))
            ->executeQuery();

        $user = $this->findOrFailById(
            (int)$this->connection->lastInsertId()
        );

        $this->syncRoles($user, $userDto);
        
        return $this->findOrFailById($user->getId());
    }

    public function update(UserDTO $userDto, User $user): User
    {
        $this->createQueryBuilder()
            ->update(UserTable::NAME)
            ->where($this->whereIdEquals())
            ->setParameter('id', $user->getId())
            ->set(UserTable::COLUMN_NAME, ':name')
            ->set(UserTable::COLUMN_EMAIL, ':email')
            ->set(UserTable::COLUMN_PASSWORD, ':password')
            ->set(UserTable::COLUMN_UPDATED_AT, ':updatedAt')
            ->setParameter('name', $userDto->getName())
            ->setParameter('email', $userDto->getEmail())
            ->setParameter('password', $userDto->getPassword())
            ->setParameter('updatedAt', (new \DateTimeImmutable())->format($this->databaseDateTimeFormat))
            ->executeQuery();

        $this->syncRoles($user, $userDto);

        return $this->findOrFailById(
            $user->getId()
        );
    }

    public function delete(User $user): void
    {
        $this->createQueryBuilder()
            ->delete(UserTable::NAME)
            ->where($this->whereIdEquals())
            ->setParameter('id', $user->getId())
            ->executeQuery();
    }

    private function createSelect(array $select = UserTable::SELECT): QueryBuilder
    {
        return $this->createQueryBuilder()->select($select)->from(UserTable::NAME);
    }

    private function whereIdEquals(): string
    {
        return sprintf('%s.%s = :id', UserTable::NAME, UserTable::COLUMN_ID);
    }

    private function whereEmailEquals(): string
    {
        return sprintf('%s.%s = :email', UserTable::NAME, UserTable::COLUMN_EMAIL);
    }

    private function whereIdInIds(): string
    {
        return sprintf('%s.%s IN :ids', UserTable::NAME, UserTable::COLUMN_ID);
    }

    private function syncRoles(User $user, UserDTO $userDTO): void
    {
        $currentRoleIds = array_map(
            static fn(Role $role): int => $role->getId(),
            $user->getRoles()
        );

        $newRoles = array_diff($userDTO->getRoleIds(), $currentRoleIds);
        
        foreach ($newRoles as $roleId) {
            $this->insertRoleForUser($roleId, $user->getId());
        }

        $deletableRoleIds = array_diff($currentRoleIds, $userDTO->getRoleIds());

        if (0 === count($deletableRoleIds)) {
            return;
        }

        $this->deleteRolesForUser($deletableRoleIds, $user->getId());
    }

    private function insertRoleForUser(int $roleId, int $userId): void
    {
        $this->createQueryBuilder()
            ->insert(UserRolePivot::NAME)
            ->values([
                UserRolePivot::COLUMN_ROLE_ID => ':roleId',
                UserRolePivot::COLUMN_USER_ID => ':userId',
            ])
            ->setParameter('roleId', $roleId)
            ->setParameter('userId', $userId)
            ->executeQuery();
    }

    private function deleteRolesForUser(array $rolesIds, int $userId): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->delete(UserRolePivot::NAME)
            ->where(sprintf('%s.%s = :userId', UserRolePivot::NAME, UserRolePivot::COLUMN_USER_ID))
            ->setParameter('userId', $userId)
            ->where($queryBuilder->expr()->or(
                ...array_map(
                    static fn (int $roleId): string => $queryBuilder->expr()->eq(
                        sprintf('%s.%s', UserRolePivot::NAME, UserRolePivot::COLUMN_ROLE_ID),
                        $roleId
                    ),
                    $rolesIds
                )
            ))
            ->delete()
            ->executeQuery();
    }

    /**
     * @param array<string, (int|string)> $userInfo
     */
    private function hydrate(array $userInfo): User
    {
        $userId = (int)$userInfo[UserTable::COLUMN_ID];

        return new User(
            $userId,
            (string)$userInfo[UserTable::COLUMN_NAME],
            (string)$userInfo[UserTable::COLUMN_EMAIL],
            (string)$userInfo[UserTable::COLUMN_PASSWORD],
            $this->roleRepository->findByUserId($userId),
        );
    }

    /**
     * @param array $userInfos
     * @return User[]
     */
    private function hydrateAll(array $userInfos): array
    {
        $userInfos = $this->keyBy('id', $userInfos);
        
        return array_map([$this, 'hydrate'], $userInfos);
    }
}
