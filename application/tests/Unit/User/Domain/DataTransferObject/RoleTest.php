<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Domain\DataTransferObject;

use PHPUnit\Framework\TestCase;
use XIP\User\Domain\DataTransferObject\Role;

/**
 * @covers \XIP\User\Domain\DataTransferObject\Role
 */
class RoleTest extends TestCase
{
    public function roleScenarioProvider(): array
    {
        return [
            'A role' => [
                'name' => 'admin',
            ],
        ];
    }
    
    /**
     * @dataProvider roleScenarioProvider
     */
    public function testRole(string $name): void
    {
        // Execute
        $result = new Role($name);
        
        // Validate
        $this->assertInstanceOf(Role::class, $result);
        $this->assertSame($name, $result->getName());
    }
}
