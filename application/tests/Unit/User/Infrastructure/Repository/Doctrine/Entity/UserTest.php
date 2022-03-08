<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\Role;
use XIP\User\Infrastructure\Repository\Doctrine\Entity\User;

/**
 * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User
 */
class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getId
     */
    public function testGetId(): void
    {
        // Validate
        $this->assertSame(0, $this->user->getId());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getName
     */
    public function testGetName(): void
    {
        // Validate
        $this->assertSame('', $this->user->getName());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setName
     */
    public function testSetName(): void
    {
        // Setup
        $name = 'Patrick Batenburg';

        // Execute
        $this->user->setName($name);

        // Validate
        $this->assertSame($name, $this->user->getName());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getEmail
     */
    public function testGetEmail(): void
    {
        // Validate
        $this->assertSame('', $this->user->getEmail());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setEmail
     */
    public function testSetEmail(): void
    {
        // Setup
        $email = 'patrick.batenburg@x-ip.nl';

        // Execute
        $this->user->setEmail($email);

        // Validate
        $this->assertSame($email, $this->user->getEmail());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getPassword
     */
    public function testGetPassword(): void
    {
        // Validate
        $this->assertNull($this->user->getPassword());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setPassword
     */
    public function testSetPassword(): void
    {
        // Setup
        $password = 'superSecret1';
        
        // Execute
        $this->user->setPassword($password);
        
        // Validate
        $this->assertSame($password, $this->user->getPassword());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getRoles
     */
    public function testGetRoles(): void
    {
        // Execute
        $result = $this->user->getRoles();
        
        // Validate
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setRoles
     */
    public function testSetRoles(): void
    {
        // Setup
        $adminRole = new Role();
        $adminRole->setName('admin');
        $roles = new ArrayCollection([0 => $adminRole]);
        
        // Execute
        $this->user->setRoles($roles);
        
        // Validate
        $this->assertSame($roles, $this->user->getRoles());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getCreatedAt
     */
    public function testGetCreatedAt(): void
    {
        // Validate
        $this->assertNull($this->user->getCreatedAt());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setCreatedAt
     */
    public function testSetCreatedAt(): void
    {
        // Setup
        $now = new \DateTimeImmutable();
        
        // Execute
        $this->user->setCreatedAt($now);
        
        // Validate
        $this->assertSame($now, $this->user->getCreatedAt());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::getUpdatedAt
     */
    public function testGetUpdatedAt(): void
    {
        // Validate
        $this->assertNull($this->user->getUpdatedAt());
    }

    /**
     * @covers \XIP\User\Infrastructure\Repository\Doctrine\Entity\User::setUpdatedAt
     */
    public function testSetUpdatedAt(): void
    {
        // Setup
        $now = new \DateTimeImmutable();

        // Execute
        $this->user->setUpdatedAt($now);

        // Validate
        $this->assertSame($now, $this->user->getUpdatedAt());
    }
}