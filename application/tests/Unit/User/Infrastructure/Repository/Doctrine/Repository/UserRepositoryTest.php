<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User as UserEntity;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository
 */
class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $classMetadata = new ClassMetadata(UserEntity::class);

        $managerRegistryMock->expects($this->once())
            ->method('getManagerForClass')
            ->with(UserEntity::class)
            ->willReturn($entityManagerMock);

        $entityManagerMock->expects($this->once())
            ->method('getClassMetadata')
            ->with(UserEntity::class)
            ->willReturn($classMetadata);

        $this->userRepository = new UserRepository($managerRegistryMock);
    }

    public function testConstruct(): void
    {
        // Validate
        $this->assertInstanceOf(UserRepository::class, $this->userRepository);
        $this->assertSame(UserEntity::class, $this->userRepository->getClassName());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Repository\UserRepository::create
     */
    public function testCreate(): void
    {
        // Execute
        $result = $this->userRepository->create();
        
        // Validate
        $this->assertInstanceOf(UserEntity::class, $result);
        $this->assertSame(0, $result->getId());
        $this->assertSame('', $result->getName());
        $this->assertSame('', $result->getEmail());
        $this->assertNull($result->getPassword());
        $this->assertEmpty($result->getRoles());
        $this->assertNull($result->getCreatedAt());
        $this->assertNull($result->getUpdatedAt());
    }
}