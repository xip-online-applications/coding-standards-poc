<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\QueryHandlerInterface;
use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;
use XIP\User\Application\Query\FindUserQuery;
use XIP\User\Application\Query\Result\UserResult;

class FindUserQueryHandler extends AbstractUserQueryHandler implements QueryHandlerInterface
{
    public static function getHandledMessages(): iterable
    {
        yield FindUserQuery::class => [
            'method' => 'handle',
            'from_transport' => 'queries_transport',
            'bus' => 'queries.bus',
        ];
    }

    public function handle(QueryInterface $query): QueryResultInterface
    {
        if (!$query instanceof FindUserQuery) {
            throw new UnexpectedTypeException($query, FindUserQuery::class);
        }
        
        return new UserResult(
            $this->userRepository->findOrFailById($query->getId())
        );
    }
}
