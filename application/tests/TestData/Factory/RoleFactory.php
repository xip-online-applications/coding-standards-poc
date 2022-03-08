<?php

declare(strict_types=1);

namespace XIP\Tests\TestData\Factory;


use XIP\User\Domain\Model\Role;

class RoleFactory extends AbstractFactory
{
    public function create(array $attributes = []): Role
    {
        return new Role(
            array_key_exists('id', $attributes) ? $attributes['id'] : $this->generator->uuid,
            array_key_exists('name', $attributes) ? $attributes['name'] : $this->generator->name,
        );
    }

    /**
     * @param int $count
     * @return array<int, Role>
     */
    public function createRoles(int $count = 2): array
    {
        return array_fill(0, $count, $this->create());
    }
}
