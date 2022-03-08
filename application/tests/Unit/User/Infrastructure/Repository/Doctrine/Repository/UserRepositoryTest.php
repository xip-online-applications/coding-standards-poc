<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User;
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
        $classMetadata = new ClassMetadata(User::class);

        $managerRegistryMock->expects($this->once())
            ->method('getManagerForClass')
            ->with(User::class)
            ->willReturn($entityManagerMock);

        $entityManagerMock->expects($this->once())
            ->method('getClassMetadata')
            ->with(User::class)
            ->willReturn($classMetadata);

        $this->userRepository = new UserRepository($managerRegistryMock);
    }

    public function testConstruct(): void
    {
        // Validate
        $this->assertInstanceOf(UserRepository::class, $this->userRepository);
        $this->assertSame(User::class, $this->userRepository->getClassName());
    }
}