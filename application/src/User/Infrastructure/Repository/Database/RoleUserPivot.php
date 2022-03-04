<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Repository\Database;

class RoleUserPivot
{
    public const NAME = 'role_user';

    public const COLUMN_ROLE_ID = 'role_id';
    public const COLUMN_USER_ID = 'user_id';
}