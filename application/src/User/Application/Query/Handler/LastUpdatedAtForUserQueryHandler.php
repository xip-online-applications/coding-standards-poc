<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\Result\LastUpdatedAtResult;
use XIP\Shared\Domain\Query\QueryHandlerInterface;
use XIP\Shared\Domain\Query\QueryInterface;
use XIP\Shared\Domain\Query\QueryResultInterface;
use XIP\User\Application\Query\LastUpdatedAtForUserQuery;

class LastUpdatedAtForUserQueryHandler extends AbstractUserQueryHandler implements QueryHandlerInterface
{
    public static function getHandledMessages(): iterable
    {
        yield LastUpdatedAtForUserQuery::class => [
            'method' => 'handle',
            'from_transport' => 'queries_transport',
            'bus' => 'queries.bus',
        ];
    }

    public function handle(QueryInterface $query): QueryResultInterface
    {
        if (!$query instanceof LastUpdatedAtForUserQuery) {
            throw new UnexpectedTypeException($query, LastUpdatedAtForUserQuery::class);
        }
        
        return new LastUpdatedAtResult(
            $this->userRepository->findLastUpdatedAtForUser($query->getUserId())
        );
    }
}