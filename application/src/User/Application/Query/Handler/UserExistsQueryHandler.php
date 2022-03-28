<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\QueryHandlerInterface;
use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;
use XIP\User\Application\Query\Result\UserExistsResult;
use XIP\User\Application\Query\UserExistsQuery;

class UserExistsQueryHandler extends AbstractUserQueryHandler implements QueryHandlerInterface
{
    public static function getHandledMessages(): iterable
    {
        yield UserExistsQuery::class => [
            'method' => 'handle',
            'from_transport' => 'queries_transport',
            'bus' => 'queries.bus',
        ];
    }

    public function handle(QueryInterface $query): QueryResultInterface
    {
        if (!$query instanceof UserExistsQuery) {
            throw new UnexpectedTypeException($query, UserExistsQuery::class);
        }
        
        return new UserExistsResult(
            $this->userRepository->exists($query->getEmail())
        );
    }
}