<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Content;

use XIP\App\Domain\Content\UserContentInterface;
use XIP\Shared\Infrastructure\Http\Content\AbstractTwigContent;
use XIP\User\Domain\Model\User;

class UserWebContent extends AbstractTwigContent implements UserContentInterface
{
    private User $user;
    
    public function setUser(User $user): self
    {
        $this->user = $user;
        
        return $this;
    }
    
    protected function getTemplateName(): string
    {
        return 'user.index';
    }

    protected function getTemplateData(): array
    {
        return ['user' => $this->user];
    }
}
