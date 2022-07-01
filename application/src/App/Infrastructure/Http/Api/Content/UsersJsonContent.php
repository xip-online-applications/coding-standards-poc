<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\App\Domain\Content\Serializer\UserSerializerInterface;
use XIP\App\Domain\Content\UsersContentInterface;
use XIP\User\Domain\Model\User;

class UsersJsonContent implements UsersContentInterface
{
    public function __construct(
        private UserSerializerInterface $userSerializer
    ) {
    }
    
    public function compose(array $users): string
    {
        return json_encode(
            array_map(
                fn(User $user): array => $this->userSerializer->serialize($user),
                $users
            )
        );
    }
}
