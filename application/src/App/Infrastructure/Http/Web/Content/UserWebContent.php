<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Content;

use XIP\App\Domain\Content\UserContentInterface;
use XIP\Shared\Infrastructure\Http\Content\AbstractTwigContent;
use XIP\User\Domain\Model\User;

class UserWebContent extends AbstractTwigContent implements UserContentInterface
{
    public function compose(User $user): string
    {
        return $this->twig->render(
            'user.show',
            ['user' => $user]
        );
    }
}
