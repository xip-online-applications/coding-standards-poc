<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Domain\Model;

use PHPUnit\Framework\TestCase;
use XIP\User\Domain\Model\Role;

/**
 * @covers \XIP\User\Domain\Model\Role
 */
class RoleTest extends TestCase
{
    public function roleScenarioProvider(): array
    {
        return [
            'A role' => [
                'id' => 1,
                'name' => 'admin',
            ],
        ];
    }
    
    /**
     * @dataProvider roleScenarioProvider
     */
    public function testRole(int $id, string $name): void
    {
        // Execute
        $result = new Role($id, $name);
        
        // Validate
        $this->assertInstanceOf(Role::class, $result);
        $this->assertSame($id, $result->getId());
        $this->assertSame($name, $result->getName());
    }
}
