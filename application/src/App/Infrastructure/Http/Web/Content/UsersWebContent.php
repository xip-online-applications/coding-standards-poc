<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Content;

use XIP\App\Domain\Content\UsersContentInterface;
use XIP\Shared\Infrastructure\Http\Content\AbstractTwigContent;
use XIP\User\Domain\Model\User;

class UsersWebContent extends AbstractTwigContent implements UsersContentInterface
{
    /**
     * @param User[] $users
     */
    public function compose(array $users): string
    {
        return $this->twig->render(
            'user.index',
            ['users' => $users]
        );
    }
}
