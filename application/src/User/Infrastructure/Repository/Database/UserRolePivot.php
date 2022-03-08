<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

class UserRolePivot
{
    public const NAME = 'user_role';

    public const COLUMN_ROLE_ID = 'role_id';
    public const COLUMN_USER_ID = 'user_id';
}