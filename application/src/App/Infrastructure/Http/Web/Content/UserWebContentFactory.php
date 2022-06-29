<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Content;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\Result\LastUpdatedAtResult;
use XIP\Shared\Domain\Bus\QueryBusInterface;
use XIP\User\Application\Query\FindUserQuery;
use XIP\User\Application\Query\LastUpdatedAtForUserQuery;
use XIP\User\Application\Query\Result\UserResult;

class UserWebContentFactory
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private UserWebContent $userWebContent,
    ) {
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
        
        return $this->userWebContent
                        ->setUser($userResult->getUser())
                        ->compose();
    }
}
