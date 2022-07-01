<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\App\Domain\Content\Serializer\UserSerializerInterface;
use XIP\App\Domain\Content\UserContentInterface;
use XIP\User\Domain\Model\User;

class UserJsonContent implements UserContentInterface
{
    public function __construct(
        private UserSerializerInterface $userSerializer
    ) {
    }
    
    public function compose(User $user): string
    {
        return json_encode(
            $this->userSerializer->serialize($user)
        );
    }
}
