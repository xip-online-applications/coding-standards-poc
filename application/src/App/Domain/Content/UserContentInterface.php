<?php

declare(strict_types=1);

namespace XIP\App\Domain\Content;

use XIP\App\Domain\Content\Serializer\UserSerializerInterface;
use XIP\User\Domain\Model\User;

interface UserContentInterface
{
    public function compose(User $user): string;
}
