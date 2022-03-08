<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Domain\DataTransferObject;

use XIP\User\Domain\DataTransferObject\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \XIP\User\Domain\DataTransferObject\User
 */
class UserTest extends TestCase
{
    public function userScenarioProvider(): array
    {
        return [
            'A new user dto' => [
                'Patrick Batenburg',
                'patrick.batenburg@x-ip.nl',
                null,
                [],
            ],
            'A confirmed user dto' => [
                'Patrick Batenburg',
                'patrick.batenburg@x-ip.nl',
                'superSecretPassword',
                [],
            ],
        ];
    }

    /**
     * @dataProvider userScenarioProvider
     */
    public function testModel(string $name, string $email, ?string $password, array $roleIds): void
    {
        // Execute
        $result = new User($name, $email, $password, $roleIds);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame($email, $result->getEmail());
        $this->assertSame($password, $result->getPassword());
        $this->assertSame($roleIds, $result->getRoleIds());
    }
}
