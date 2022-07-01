<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\App\Domain\Content\UsersContentInterface;
use XIP\User\Domain\Model\User;

class UsersJsonContent implements UsersContentInterface
{
    public function compose(array $users): string
    {
        return json_encode(
            array_map(
                static fn(User $user): array => UserSerializer::serialize($user),
                $users
            )
        );
    }
}
