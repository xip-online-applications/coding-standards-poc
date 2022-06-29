<?php

declare(strict_types=1);

namespace XIP\App\Domain\Content;

use XIP\Shared\Infrastructure\Http\Content\ContentInterface;
use XIP\User\Domain\Model\User;

interface UserContentInterface extends ContentInterface
{
    public function setUser(User $user): self;
}