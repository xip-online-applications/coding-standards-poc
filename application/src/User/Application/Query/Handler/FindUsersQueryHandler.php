<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Domain\Query\QueryHandlerInterface;
use XIP\Shared\Domain\Query\QueryInterface;
use XIP\Shared\Domain\Query\QueryResultInterface;
use XIP\User\Application\Query\FindUsersQuery;
use XIP\User\Application\Query\Result\UsersResult;

class FindUsersQueryHandler extends AbstractUserQueryHandler implements QueryHandlerInterface
{
    public static function getHandledMessages(): iterable
    {
        yield FindUsersQuery::class => [
            'method' => 'handle',
            'from_transport' => 'queries_transport',
            'bus' => 'queries.bus',
        ];
    }

    public function handle(QueryInterface $query): QueryResultInterface
    {
        if (!$query instanceof FindUsersQuery) {
            throw new UnexpectedTypeException($query, FindUsersQuery::class);
        }

        return new UsersResult(
            $this->userRepository->findAll()
        );
    }
}