<?php

declare(strict_types=1);

namespace XIP\User\Application\Query;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Application\Query\QueryHandlerInterface;
use XIP\Shared\Application\Query\QueryInterface;
use XIP\Shared\Application\Query\QueryResultInterface;

class FindUserByEmailQueryHandler extends AbstractUserQueryHandler implements QueryHandlerInterface
{
    public static function getHandledMessages(): iterable
    {
        yield FindUserByEmailQuery::class => [
            'method' => 'handle',
            'from_transport' => 'queries_transport',
            'bus' => 'queries.bus',
        ];
    }

    public function handle(QueryInterface $query): QueryResultInterface
    {
        if (!$query instanceof FindUserByEmailQuery) {
            throw new UnexpectedTypeException($query, FindUserByEmailQuery::class);
        }
        
        return new UserResult(
            $this->userRepository->findOrFailByEmail($query->getEmail())
        );
    }
}
