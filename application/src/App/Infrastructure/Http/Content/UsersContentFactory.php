<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Content;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\App\Domain\Content\UsersContentInterface;
use XIP\Shared\Application\Query\Result\LastUpdatedAtResult;
use XIP\Shared\Domain\Bus\QueryBusInterface;
use XIP\User\Application\Query\FindUsersQuery;
use XIP\User\Application\Query\LastUpdatedAtForUsersQuery;
use XIP\User\Application\Query\Result\UsersResult;

class UsersContentFactory
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }

    public function getLastUpdatedAt(): \DateTimeInterface
    {
        $lastUpdatedAtResult = $this->queryBus->query(new LastUpdatedAtForUsersQuery());

        if (!$lastUpdatedAtResult instanceof LastUpdatedAtResult) {
            throw new UnexpectedTypeException($lastUpdatedAtResult, LastUpdatedAtResult::class);
        }

        return $lastUpdatedAtResult->getLastUpdatedAt();
    }
    
    public function build(UsersContentInterface $usersContent): string
    {
        $usersResult = $this->queryBus->query(new FindUsersQuery());
        
        if (!$usersResult instanceof UsersResult) {
            throw new UnexpectedTypeException($usersResult, UsersResult::class);
        }
        
        return $usersContent->compose($usersResult->getUsers());
    }
}