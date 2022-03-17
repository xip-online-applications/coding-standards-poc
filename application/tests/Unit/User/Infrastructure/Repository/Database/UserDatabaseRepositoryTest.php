<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Repository\Database;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XIP\User\Infrastructure\Repository\Database\UserDatabaseRepository;
use XIP\User\Infrastructure\Repository\RoleRepositoryInterface;

class UserDatabaseRepositoryTest extends TestCase
{
    private MockObject|Connection $connectionMock;
    
    private MockObject|RoleRepositoryInterface $roleRepositoryMock;
    
    private UserDatabaseRepository $userDatabaseRepository;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->connectionMock = $this->createMock(Connection::class);
        $dateTimeFormat = 'Y-m-d H:i:s';
        $this->roleRepositoryMock = $this->createMock(RoleRepositoryInterface::class);
        $this->userDatabaseRepostiory = new UserDatabaseRepository(
            $this->connectionMock,
            $dateTimeFormat,
            $this->roleRepositoryMock
        );
    }
}