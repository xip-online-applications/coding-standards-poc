<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Domain\Model;

use XIP\User\Domain\Model\Role;
use XIP\User\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers \XIP\User\Domain\Model\User
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
                'roles' => [1 => new Role(1, 'admin')],
            ],
            'A user with a confirmed account' => [
                'id' => 1 ,
                'name' => 'Patrick Batenburg',
                'mail' => 'patrick.batenburg@x-ip.nl',
                'password' => 'superSecretPassword',
                'roles' => [1 => new Role(1, 'admin')],

            ],
            'An user with an invalid email' => [
                'id' => 1 ,
                'name' => 'Patrick Batenburg',
                'mail' => 'patrick.batenburg',
                'password' => 'superSecretPassword',
                'roles' => [1 => new Role(1, 'admin')],
            ]
        ];
    }

    /**
     * @dataProvider userScenarioProvider
     */
    public function testModel(int $id, string $name, string $email, ?string $password, array $roles): void
    {
        // Execute
        $result = new User($id, $name, $email, $password, $roles);

        // Validate
        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($id, $result->getId());
        $this->assertSame($name, $result->getName());
        $this->assertSame($email, $result->getEmail());
        $this->assertSame($password, $result->getPassword());
        $this->assertSame($roles, $result->getRoles());
    }
}
