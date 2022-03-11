<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XIP\Shared\Domain\Exception\ModelNotFoundException;
use XIP\Tests\TestData\Factory\UserFactory;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Domain\Model\Role;
use XIP\User\Domain\Model\User;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role as RoleEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository as RoleEntityRepository;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository as UserEntityRepository;
use XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository
 */
class UserDoctrineRepositoryTest extends TestCase
{
    private UserEntityRepository|MockObject $userEntityRepositoryMock;
    
    private RoleEntityRepository|MockObject $roleEntityRepositoryMock;

    private QueryBuilder|MockObject $queryBuilderMock;

    private AbstractQuery|MockObject $queryMock;

    private UserFactory $userFactory;
    
    private UserDoctrineRepository $userDoctrineRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userEntityRepositoryMock = $this->createMock(UserEntityRepository::class);
        $this->roleEntityRepositoryMock = $this->createMock(RoleEntityRepository::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->queryMock = $this->createMock(AbstractQuery::class);
        $this->userDoctrineRepository = new UserDoctrineRepository(
            $this->userEntityRepositoryMock,
            $this->roleEntityRepositoryMock
        );
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findAll
     */
    public function testFindAll(): void
    {
        // Setup
        $userEntities = [
            1 => $this->createUserEntity(
                1,
                'Youri Lefers',
                'youri.lefers@x-ip.nl',
                'superSecret',
                new ArrayCollection([$this->createRoleEntity(1, 'admin')])
            ),
            2 => $this->createUserEntity(
                2,
                'Patrick Batenburg',
                'patrick.batenburg@x-ip.nl',
                'evenMoreSecret',
                new ArrayCollection([$this->createRoleEntity(2, 'teacher')])
            )
        ];
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('user')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('indexBy')
            ->with('user', 'user.id')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->queryMock);
        $this->queryMock->expects($this->once())
            ->method('execute')
            ->willReturn($userEntities);

        // Execute
        $results = $this->userDoctrineRepository->findAll();

        // Validate
        $this->assertCount(2, $results);

        $this->assertArrayHasKey(1, $results);
        $this->assertInstanceOf(User::class, $user = $results[1]);
        $this->assertSame(1, $user->getId());
        $this->assertSame('Youri Lefers', $user->getName());
        $this->assertSame('youri.lefers@x-ip.nl', $user->getEmail());
        $this->assertSame('superSecret', $user->getPassword());
        $this->assertEquals([1 => new Role(1, 'admin')], $user->getRoles());

        $this->assertArrayHasKey(2, $results);
        $this->assertInstanceOf(User::class, $user = $results[2]);
        $this->assertSame(2, $user->getId());
        $this->assertSame('Patrick Batenburg', $user->getName());
        $this->assertSame('patrick.batenburg@x-ip.nl', $user->getEmail());
        $this->assertSame('evenMoreSecret', $user->getPassword());
        $this->assertEquals([2 => new Role(2, 'teacher')], $user->getRoles());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findByIds
     */
    public function testFindByIds(): void
    {
        // Setup
        $ids = [1, 2];
        $userEntities = [
            1 => $this->createUserEntity(
                1,
                'Youri Lefers',
                'youri.lefers@x-ip.nl',
                'superSecret',
                new ArrayCollection([$this->createRoleEntity(1, 'admin')])
            ),
            2 => $this->createUserEntity(
                2,
                'Patrick Batenburg',
                'patrick.batenburg@x-ip.nl',
                'evenMoreSecret',
                new ArrayCollection([$this->createRoleEntity(2, 'teacher')])
            )
        ];
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('user')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('indexBy')
            ->with('user', 'user.id')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('where')
            ->with('user.id in :ids')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('ids', $ids)
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->queryMock);
        $this->queryMock->expects($this->once())
            ->method('execute')
            ->willReturn($userEntities);

        // Execute
        $results = $this->userDoctrineRepository->findByIds($ids);

        // Validate
        $this->assertCount(2, $results);

        $this->assertArrayHasKey(1, $results);
        $this->assertInstanceOf(User::class, $user = $results[1]);
        $this->assertSame(1, $user->getId());
        $this->assertSame('Youri Lefers', $user->getName());
        $this->assertSame('youri.lefers@x-ip.nl', $user->getEmail());
        $this->assertSame('superSecret', $user->getPassword());
        $this->assertEquals([1 => new Role(1, 'admin')], $user->getRoles());

        $this->assertArrayHasKey(2, $results);
        $this->assertInstanceOf(User::class, $user = $results[2]);
        $this->assertSame(2, $user->getId());
        $this->assertSame('Patrick Batenburg', $user->getName());
        $this->assertSame('patrick.batenburg@x-ip.nl', $user->getEmail());
        $this->assertSame('evenMoreSecret', $user->getPassword());
        $this->assertEquals([2 => new Role(2, 'teacher')], $user->getRoles());
    }

    public function findByIdScenarioProvider(): array
    {
        return [
            'A non existing user' => [
                null,
                null,
                0,
            ],
            'An existing user' => [
                new User(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    [1 => new Role(1, 'admin')]
                ),
                $this->createUserEntity(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    new ArrayCollection([$this->createRoleEntity(1, 'admin')])
                ),
                1,
            ]
        ];
    }

    /**
     * @dataProvider findByIdScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findById
     */
    public function testFindById(?User $expected, ?UserEntity $userEntity, int $id): void
    {
        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->findById($id);

        // Validate
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider findByIdScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findOrFailById
     */
    public function testFindOrFailById(?User $expected, ?UserEntity $userEntity, int $id): void
    {
        // Expectation
        if (null === $expected) {
            $this->expectException(ModelNotFoundException::class);
        }

        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->findOrFailById($id);

        // Validate
        if (null !== $expected) {
            $this->assertEquals($expected, $result);
        }
    }

    public function findByEmailScenarioProvider(): array
    {
        return [
            'A non existing user' => [
                null,
                null,
                'non-existing@email.com',
            ],
            'An existing user' => [
                new User(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    [1  => new Role(1, 'admin')],
                ),
                $this->createUserEntity(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    new ArrayCollection([$this->createRoleEntity(1, 'admin')])
                ),
                'patrick.batenburg@x-ip.nl',
            ],
        ];
    }

    /**
     * @dataProvider findByEmailScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findByEmail
     */
    public function testFindByEmail(?User $expected, ?UserEntity $userEntity, string $email): void
    {
        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->findByEmail($email);

        // Validate
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider findByEmailScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::findByEmail
     */
    public function testFindOrFailByEmail(?User $expected, ?UserEntity $userEntity, string $email): void
    {
        // Expectation
        if (null === $expected) {
            $this->expectException(ModelNotFoundException::class);
        }

        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->findOrFailByEmail($email);

        // Validate
        if (null !== $expected) {
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * @dataProvider findByEmailScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::exists
     */
    public function testExists(?User $expected, ?UserEntity $userEntity, string $email): void
    {
        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->exists($email);

        // Validate
        $this->assertSame(null !== $expected, $result);
    }

    /**
     * @dataProvider findByEmailScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::unique
     */
    public function testUnique(?User $expected, ?UserEntity $userEntity, string $email): void
    {
        // Setup
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($userEntity);

        // Execute
        $result = $this->userDoctrineRepository->unique($email);

        // Validate
        $this->assertSame(null === $expected, $result);
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::store
     */
    public function testStore(): void
    {
        // Setup
        $userDto = new UserDto(
            'Patrick Batenburg',
            'patrick.batenburg@x-ip.nl',
            'superSecret',
            [1]
        );
        $roles = [
            1 => $this->createMock(RoleEntity::class)
        ];

        $userEntityMock = $this->createMock(UserEntity::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $unitOfWorkMock = $this->createMock(UnitOfWork::class);

        $this->userEntityRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn($userEntityMock);
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('user')
            ->willReturn($this->queryBuilderMock);

        $this->queryBuilderMock->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);
        $this->roleEntityRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['id' => [1]])
            ->willReturn($roles);

        $userEntityMock->expects($this->once())
            ->method('setName')
            ->with('Patrick Batenburg');
        $userEntityMock->expects($this->once())
            ->method('setEmail')
            ->with('patrick.batenburg@x-ip.nl');
        $userEntityMock->expects($this->once())
            ->method('setPassword')
            ->with('superSecret');
        $userEntityMock->expects($this->once())
            ->method('setRoles')
            ->with(new ArrayCollection($roles));
        $userEntityMock->expects($this->once())
            ->method('setCreatedAt');
        $userEntityMock->expects($this->once())
            ->method('setUpdatedAt');
        $userEntityMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $entityManagerMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);
        $unitOfWorkMock->expects($this->once())
            ->method('isInIdentityMap')
            ->with($userEntityMock)
            ->willReturn(false);
        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($userEntityMock);
        $entityManagerMock->expects($this->once())
            ->method('flush');
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(
                $this->createUserEntity(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    new ArrayCollection([$this->createRoleEntity(1, 'admin')])
                )
            );

        // Execute
        $result = $this->userDoctrineRepository->store($userDto);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame(1, $result->getId());
        $this->assertSame('Patrick Batenburg', $result->getName());
        $this->assertSame('patrick.batenburg@x-ip.nl', $result->getEmail());
        $this->assertSame('superSecret', $result->getPassword());
        $this->assertEquals([1 => new Role(1, 'admin')], $result->getRoles());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::update
     */
    public function testUpdate(): void
    {
        // Setup
        $user = new User(
            1,
            'Batenburg',
            'batenburg@x-ip.nl',
            'secret',
            [2]
        );
        $userDto = new UserDto(
            'Patrick Batenburg',
            'patrick.batenburg@x-ip.nl',
            'superSecret',
            [1]
        );
        $roles = [
            1 => $this->createMock(RoleEntity::class)
        ];

        $userEntityMock = $this->createMock(UserEntity::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $unitOfWorkMock = $this->createMock(UnitOfWork::class);

        $this->userEntityRepositoryMock->expects($this->exactly(2))
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturnOnConsecutiveCalls(
                $userEntityMock,
                $this->createUserEntity(
                    1,
                    'Patrick Batenburg',
                    'patrick.batenburg@x-ip.nl',
                    'superSecret',
                    new ArrayCollection([$this->createRoleEntity(1, 'admin')])
                )
            );
        $this->userEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('user')
            ->willReturn($this->queryBuilderMock);

        $this->queryBuilderMock->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);
        $this->roleEntityRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['id' => [1]])
            ->willReturn($roles);

        $userEntityMock->expects($this->once())
            ->method('setName')
            ->with('Patrick Batenburg');
        $userEntityMock->expects($this->once())
            ->method('setEmail')
            ->with('patrick.batenburg@x-ip.nl');
        $userEntityMock->expects($this->once())
            ->method('setPassword')
            ->with('superSecret');
        $userEntityMock->expects($this->once())
            ->method('setRoles')
            ->with(new ArrayCollection($roles));
        $userEntityMock->expects($this->never())
            ->method('setCreatedAt');
        $userEntityMock->expects($this->once())
            ->method('setUpdatedAt');

        $entityManagerMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);
        $unitOfWorkMock->expects($this->once())
            ->method('isInIdentityMap')
            ->with($userEntityMock)
            ->willReturn(true);
        $entityManagerMock->expects($this->once())
            ->method('persist')
            ->with($userEntityMock);
        $entityManagerMock->expects($this->once())
            ->method('flush');

        // Execute
        $result = $this->userDoctrineRepository->update($userDto, $user);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame(1, $result->getId());
        $this->assertSame('Patrick Batenburg', $result->getName());
        $this->assertSame('patrick.batenburg@x-ip.nl', $result->getEmail());
        $this->assertSame('superSecret', $result->getPassword());
        $this->assertEquals([1 => new Role(1, 'admin')], $result->getRoles());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\UserDoctrineRepository::delete
     */
    public function testDelete(): void
    {
        // Setup
        $user = new User(
            1,
            'Patrick Batenburg',
            'patrick.batenburg@x-ip.nl',
            'superSecret',
            [1 => new Role(1, 'admin')],
        );
        $userEntityMock = $this->createMock(UserEntity::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->userEntityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturnOnConsecutiveCalls($userEntityMock);

        $this->userEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('user')
            ->willReturn($this->queryBuilderMock);

        $this->queryBuilderMock->expects($this->once())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($userEntityMock);
        $entityManagerMock->expects($this->once())
            ->method('flush');

        // Execute
        $this->userDoctrineRepository->delete($user);
    }

    private function createUserEntity(
        int $id,
        string $name,
        string $email,
        string $password,
        ArrayCollection $roles
    ): UserEntity {
        $userEntity  = $this->createMock(UserEntity::class);

        $userEntity->expects($this->once())->method('getId')->willReturn($id);
        $userEntity->expects($this->once())->method('getName')->willReturn($name);
        $userEntity->expects($this->once())->method('getEmail')->willReturn($email);
        $userEntity->expects($this->once())->method('getPassword')->willReturn($password);
        $userEntity->expects($this->once())->method('getRoles')->willReturn($roles);

        return $userEntity;
    }

    private function createRoleEntity(int $id, string $name): RoleEntity
    {
        $roleEntity = $this->createMock(RoleEntity::class);

        $roleEntity->expects($this->exactly(2))->method('getId')->willReturn($id);
        $roleEntity->expects($this->once())->method('getName')->willReturn($name);

        return $roleEntity;
    }
}