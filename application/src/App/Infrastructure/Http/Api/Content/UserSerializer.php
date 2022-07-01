<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\User\Domain\Model\User;

class UserSerializer
{
    public static function serialize(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
        ];
    }
}
