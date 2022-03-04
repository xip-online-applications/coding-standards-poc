<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

final class UserTable
{
    public const NAME = 'user';
    
    public const COLUMN_ID = 'id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_EMAIL = 'email';
    public const COLUMN_PASSWORD = 'password';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
    
    public const SELECT = [
        self::COLUMN_ID,
        self::COLUMN_NAME,
        self::COLUMN_EMAIL,
        self::COLUMN_PASSWORD,
    ];
}
