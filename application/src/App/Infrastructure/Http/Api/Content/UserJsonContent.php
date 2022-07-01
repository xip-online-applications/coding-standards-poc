<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\App\Domain\Content\UserContentInterface;
use XIP\User\Domain\Model\User;

class UserJsonContent implements UserContentInterface
{
    public function compose(User $user): string
    {
        return json_encode(
            UserSerializer::serialize($user)
        );
    }
}
