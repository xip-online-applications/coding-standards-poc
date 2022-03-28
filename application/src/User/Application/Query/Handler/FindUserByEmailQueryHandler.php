<?php

declare(strict_types=1);

namespace XIP\User\Application\Query\Handler;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Domain\Query\QueryHandlerInterface;
use XIP\Shared\Domain\Query\QueryInterface;
use XIP\Shared\Domain\Query\QueryResultInterface;
use XIP\User\Application\Query\FindUserByEmailQuery;
use XIP\User\Application\Query\Result\UserResult;

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
