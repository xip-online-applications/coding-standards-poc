<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Content;

use XIP\App\Domain\Content\UserContentInterface;
use XIP\User\Domain\Model\User;

class UserJsonContent implements UserContentInterface
{
    private User $user;
    
    public function setUser(User $user): self
    {
        $this->user = $user;
        
        return $this;
    }

    public function compose(): string
    {
        return json_encode(
            [
                'id' => $this->user->getId(),
                'name' => $this->user->getName(),
            ]
        );
    }
}