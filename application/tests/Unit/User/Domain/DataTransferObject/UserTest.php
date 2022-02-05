<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\DataTransferObject;

use App\User\Domain\DataTransferObject\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\User\Domain\DataTransferObject\User
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
            ],
            'A confirmed user dto' => [
                'Patrick Batenburg',
                'patrick.batenburg@x-ip.nl',
                'superSecretPassword',
            ],
        ];
    }

    /**
     * @dataProvider userScenarioProvider
     */
    public function testModel(string $name, string $email, ?string $password): void
    {
        // Execute
        $result = new User($name, $email, $password);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($name, $result->getName());
        $this->assertSame($email, $result->getEmail());
        $this->assertSame($password, $result->getPassword());
    }
}
