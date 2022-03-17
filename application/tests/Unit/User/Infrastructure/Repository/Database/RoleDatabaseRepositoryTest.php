<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XIP\User\Domain\Model\Role;
use XIP\User\Infrastructure\Repository\Database\RoleDatabaseRepository;

/**
 * @covers \XIP\User\Infrastructure\Repository\Database\RoleDatabaseRepository
 */
class RoleDatabaseRepositoryTest extends TestCase
{
    private MockObject|Connection $connectionMock;

    private MockObject|QueryBuilder $queryBuilderMock;

    private MockObject|Result $resultMock;

    private RoleDatabaseRepository $roleDatabaseRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connectionMock = $this->createMock(Connection::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->resultMock = $this->createMock(Result::class);
        $databaseDateTimeFormat = 'Y-m-d H:i:s';
        $this->roleDatabaseRepository = new RoleDatabaseRepository(
            $this->connectionMock,
            $databaseDateTimeFormat
        );
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Database\RoleDatabaseRepository::findByUserId
     */
    public function testFindByUserId(): void
    {
        // Setup
        $userId = 1;

        $this->connectionMock->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('select')
            ->with(['id', 'name'])
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('from')
            ->with('role')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('innerJoin')
            ->with('role', 'user_role', 'user_role', 'role.id = user_role.role_id')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('where')
            ->with('user_role.user_id = :userId')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('userId', $userId)
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('executeQuery')
            ->willReturn($this->resultMock);
        $this->resultMock->expects($this->once())
            ->method('fetchAllAssociative')
            ->willReturn([
                ['id' => 1, 'name' => 'admin'],
                ['id' => 2, 'name' => 'teacher'],
                ['id' => 3, 'name' => 'student'],
            ]);

        // Execute
        $results = $this->roleDatabaseRepository->findByUserId($userId);

        // Validate
        $this->assertIsArray($results);
        $this->assertCount(3, $results);

        $this->assertArrayHasKey(1, $results);
        $this->assertInstanceOf(Role::class, $role = $results[1]);
        $this->assertSame(1, $role->getId());
        $this->assertSame('admin', $role->getName());

        $this->assertArrayHasKey(2, $results);
        $this->assertInstanceOf(Role::class, $role = $results[2]);
        $this->assertSame(2, $role->getId());
        $this->assertSame('teacher', $role->getName());

        $this->assertArrayHasKey(3, $results);
        $this->assertInstanceOf(Role::class, $role = $results[3]);
        $this->assertSame(3, $role->getId());
        $this->assertSame('student', $role->getName());
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
     * @covers \XIP\User\Infrastructure\Repository\Database\RoleDatabaseRepository::exists
     */
    public function testExists(bool $expected, int $numberOfIds, array $ids): void
    {
        // Setup
        $this->connectionMock->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('select')
            ->with(['count(role.id)'])
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('from')
            ->with('role')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('where')
            ->with('role.id in (:ids)')
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->with('ids', implode(', ', $ids))
            ->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->expects($this->once())
            ->method('executeQuery')
            ->willReturn($this->resultMock);
        $this->resultMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn($numberOfIds);
        
        // Execute
        $result = $this->roleDatabaseRepository->exists($ids);
        
        // Validate
        $this->assertSame($expected, $result);
    }
}