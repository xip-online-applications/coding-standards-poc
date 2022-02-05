<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain\Model;

use App\User\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\User\Domain\Model\User
 */
class UserTest extends TestCase
{
    public function userScenarioProvider(): array
    {
        return [
            'A new registered user' => [
                'id' => 1,
                'name' => 'Patrick Batenburg',
                'email' => 'patrick.batenburg@x-ip.nl',
                'password' => null,
            ],
            'A user with a confirmed account' => [
                'id' => 1 ,
                'name' => 'Patrick Batenburg',
                'mail' => 'patrick.batenburg@x-ip.nl',
                'password' => 'superSecretPassword',
            ],
            'An user with an invalid email' => [
                'id' => 1 ,
                'name' => 'Patrick Batenburg',
                'mail' => 'patrick.batenburg',
                'password' => 'superSecretPassword',
            ]
        ];
    }

    /**
     * @dataProvider userScenarioProvider
     */
    public function testModel(int $id, string $name, string $email, ?string $password): void
    {
        // Execute
        $result = new User($id, $name, $email, $password);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($id, $result->getId());
        $this->assertSame($name, $result->getName());
        $this->assertSame($email, $result->getEmail());
        $this->assertSame($password, $result->getPassword());
    }
}
