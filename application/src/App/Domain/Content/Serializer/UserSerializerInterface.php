<?php

declare(strict_types=1);

namespace XIP\App\Domain\Content\Serializer;

use XIP\User\Domain\Model\User;

interface UserSerializerInterface
{
    public function serialize(User $user): array;
}