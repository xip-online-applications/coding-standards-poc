<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

class RoleTable
{
    public const NAME = 'role';

    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';

    public const SELECT = [
        self::COLUMN_ID,
        self::COLUMN_NAME,
    ];
}