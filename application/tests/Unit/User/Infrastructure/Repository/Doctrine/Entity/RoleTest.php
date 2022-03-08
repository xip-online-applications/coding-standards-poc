<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\Role
 */
class RoleTest extends TestCase
{
    private Role $role;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->role = new Role();
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\Role::getId
     */
    public function testGetId(): void
    {
        // Validate
        $this->assertSame(0, $this->role->getId());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\Role::getName
     */
    public function testGetName(): void
    {
        // Validate
        $this->assertSame('', $this->role->getName());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\Role::setName
     */
    public function testName(): void
    {
        // Setup
        $role = 'admin';
        
        // Execute
        $this->role->setName($role);
        
        // Validate
        $this->assertSame($role, $this->role->getName());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\Role::getUsers
     */
    public function testGetUsers(): void
    {
        // Execute
        $result = $this->role->getUsers();
        
        // Validate
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }
}