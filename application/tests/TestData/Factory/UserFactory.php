<?php

namespace XIP\Tests\TestData\Factory;

use XIP\User\Domain\Model\User;
use JetBrains\PhpStorm\Pure;

class UserFactory extends AbstractFactory
{
    #[Pure] public function create(array $attributes = []): User
    {
        return new User(
            array_key_exists('id', $attributes) ? $attributes['id'] : $this->generator->uuid,
            array_key_exists('name', $attributes) ? $attributes['name'] : $this->generator->name,
            array_key_exists('email', $attributes) ? $attributes['email'] : $this->generator->email,
            array_key_exists('password', $attributes) ? $attributes['password'] : $this->generator->password,
        );
    }
}
