<?php

namespace XIP\App\Infrastructure\Http\Web\Content;

use XIP\Shared\Infrastructure\Http\Content\AbstractTwigContent;
use XIP\User\Domain\Model\User;

class UsersWebContent extends AbstractTwigContent
{
    /** @var User[] */
    private array $users;
    
    /**
     * @param User[] $users
     */
    public function setUsers(array $users): self
    {
        $this->users = $users;
        
        return $this;
    }
    
    protected function getTemplateName(): string
    {
        return 'user.index';
    }

    protected function getTemplateData(): array
    {
        return ['users' => $this->users];
    }
}
