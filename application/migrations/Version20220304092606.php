<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use XIP\User\Infrastructure\Repository\Database\RoleTable;

final class Version20220304092606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the roles table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(RoleTable::NAME);

        $table->addColumn(RoleTable::COLUMN_ID, 'integer', ['autoincrement' => true]);
        $table->addColumn(RoleTable::COLUMN_NAME, 'string', ['notnull' => true]);

        $table->setPrimaryKey([RoleTable::COLUMN_ID]);
        $table->addUniqueIndex([RoleTable::COLUMN_NAME]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(RoleTable::NAME);
    }
}
