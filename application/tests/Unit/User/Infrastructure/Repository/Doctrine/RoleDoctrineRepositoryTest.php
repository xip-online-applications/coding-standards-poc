<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XIP\User\Domain\Model\Role;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role as RoleEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository as RoleEntityRepository;
use XIP\User\Infrastructure\Repository\Doctrine\RoleDoctrineRepository;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\RoleDoctrineRepository
 */
class RoleDoctrineRepositoryTest extends TestCase
{
    private RoleEntityRepository|MockObject $roleEntityRepositoryMock;

    private QueryBuilder|MockObject $queryBuilderMock;

    private AbstractQuery|MockObject $queryMock;
    
    private RoleDoctrineRepository $roleDoctrineRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleEntityRepositoryMock = $this->createMock(RoleEntityRepository::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->queryMock = $this->createMock(AbstractQuery::class);
        $this->roleDoctrineRepository = new RoleDoctrineRepository($this->roleEntityRepositoryMock);
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\RoleDoctrineRepository::findByUserId
     */
    public function testFindByUserId(): void
    {
        // Setup
        $userId = 1;
        $roleEntities = [
            1 => $this->createRoleEntity(1, 'admin'),
            2 => $this->createRoleEntity(2, 'teacher'),
        ];
        $this->roleEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('role')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('indexBy')
            ->with('role', 'role.id')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('innerJoin')
            ->with('role.users',  'user')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('where')
            ->with('user.id = :userId')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('userId', $userId)
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->queryMock);
        $this->queryMock->expects($this->once())
            ->method('execute')
            ->willReturn($roleEntities);

        // Execute
        $result = $this->roleDoctrineRepository->findByUserId($userId);

        // Validate
        $this->assertCount(2, $result);
        
        $this->assertArrayHasKey(1, $result);
        $this->assertInstanceOf(Role::class, $role = $result[1]);
        $this->assertSame(1, $role->getId());
        $this->assertSame('admin', $role->getName());

        $this->assertArrayHasKey(2, $result);
        $this->assertInstanceOf(Role::class, $role = $result[2]);
        $this->assertSame(2, $role->getId());
        $this->assertSame('teacher', $role->getName());
    }
    
    public function existsScenarioProvider(): array
    {
        return [
            'all ids exists' => [
                'expected' => true,
                'numberOfIds' => 3,
                'ids' => [1, 2, 3],
            ],
            'One or more ids does not exists' => [
                'expected' => false,
                'numberOfIds' => 2,
                'ids' => [2, 3, 4],
            ],
        ];
    }

    /**
     * @dataProvider existsScenarioProvider
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\RoleDoctrineRepository::exists
     */
    public function testExists(bool $expected, int $numberOfIds, array $ids): void
    {
        // Setup
        $this->roleEntityRepositoryMock->expects($this->once())
            ->method('createQueryBuilder')
            ->with('role')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('select')
            ->with('count(role.id)')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('where')
            ->with('role.id in (:ids)')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('ids', $ids)
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->queryMock);
        $this->queryMock->expects($this->once())
            ->method('getSingleScalarResult')
            ->willReturn($numberOfIds);
        
        // Execute
        $result = $this->roleDoctrineRepository->exists($ids);
        
        // Validate
        $this->assertSame($expected, $result);
    }

    private function createRoleEntity(int $id, string $name): RoleEntity
    {
        $roleEntity = $this->createMock(RoleEntity::class);
        
        $roleEntity->expects($this->once())->method('getId')->willReturn($id);
        $roleEntity->expects($this->once())->method('getName')->willReturn($name);
        
        return $roleEntity;
    }
}
