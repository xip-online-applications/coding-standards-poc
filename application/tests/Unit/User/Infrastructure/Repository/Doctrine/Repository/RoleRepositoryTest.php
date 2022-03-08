<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role;
use XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\Repository\RoleRepository
 */
class RoleRepositoryTest extends TestCase
{
    private RoleRepository $roleRepository;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $classMetadata = new ClassMetadata(Role::class);

        $managerRegistryMock->expects($this->once())
            ->method('getManagerForClass')
            ->with(Role::class)
            ->willReturn($entityManagerMock);
        
        $entityManagerMock->expects($this->once())
            ->method('getClassMetadata')
            ->with(Role::class)
            ->willReturn($classMetadata);
        
        $this->roleRepository = new RoleRepository($managerRegistryMock);
    }
    
    public function testConstruct(): void
    {
        // Validate
        $this->assertInstanceOf(RoleRepository::class, $this->roleRepository);
        $this->assertSame(Role::class, $this->roleRepository->getClassName());
    }
}