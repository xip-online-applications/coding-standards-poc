<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content\Serializer;

use XIP\App\Domain\Content\Serializer\UserSerializerInterface;
use XIP\User\Domain\Model\User;

class UserSerializer implements UserSerializerInterface
{
    public function serialize(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
        ];
    }
}
