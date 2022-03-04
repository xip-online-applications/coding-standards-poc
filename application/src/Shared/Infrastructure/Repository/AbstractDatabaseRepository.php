<?php

namespace XIP\Shared\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractDatabaseRepository
{
    protected Connection $connection;

    protected string $databaseDateTimeFormat;

    public function __construct(Connection $connection, string $databaseDateTimeFormat)
    {
        $this->connection = $connection;
        $this->databaseDateTimeFormat = $databaseDateTimeFormat;
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    protected function keyBy(string $keyField, array $array): array
    {
        $keys = array_map(
            static fn(array $value): mixed => $value[$keyField],
            $array
        );

        return array_fill_keys($keys, $array);
    }
}