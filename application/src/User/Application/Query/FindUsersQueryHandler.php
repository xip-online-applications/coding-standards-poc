<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\QueryHandlerInterface;
use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;

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