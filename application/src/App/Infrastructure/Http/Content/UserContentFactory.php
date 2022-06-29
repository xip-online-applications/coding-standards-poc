<?php

namespace XIP\App\Infrastructure\Http\Content;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\App\Domain\Content\UserContentInterface;
use XIP\Shared\Application\Query\Result\LastUpdatedAtResult;
use XIP\Shared\Domain\Bus\QueryBusInterface;
use XIP\User\Application\Query\FindUserQuery;
use XIP\User\Application\Query\LastUpdatedAtForUserQuery;
use XIP\User\Application\Query\Result\UserResult;

class UserContentFactory
{
    private UserContentInterface $userContent;
    
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }
    
    public function setUserContent(UserContentInterface $userContent): self
    {
        $this->userContent = $userContent;
        
        return $this;
    }
    
    public function getLastUpdatedAt(int $userId): \DateTimeInterface
    {
        $lastUpdatedAtResult = $this->queryBus->query(new LastUpdatedAtForUserQuery($userId));

        if (!$lastUpdatedAtResult instanceof LastUpdatedAtResult) {
            throw new UnexpectedTypeException($lastUpdatedAtResult, LastUpdatedAtResult::class);
        }

        return $lastUpdatedAtResult->getLastUpdatedAt();
    }
    
    public function build(int $userId): string
    {
        $userResult = $this->queryBus->query(new FindUserQuery($userId));

        if (!$userResult instanceof UserResult) {
            throw new UnexpectedTypeException($userResult, UserResult::class);
        }

        return $this->userContent
            ->setUser($userResult->getUser())
            ->compose();
    }
}