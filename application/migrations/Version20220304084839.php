<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use XIP\User\Infrastructure\Repository\Database\UserTable;

final class Version20220304084839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(UserTable::NAME);

        $table->addColumn(UserTable::COLUMN_ID, 'integer', ['autoincrement' => true]);
        $table->addColumn(UserTable::COLUMN_NAME, 'string', ['notnull' => true]);
        $table->addColumn(UserTable::COLUMN_EMAIL, 'string', ['notnull' => true]);
        $table->addColumn(UserTable::COLUMN_PASSWORD, 'string', ['notnull' => false]);
        $table->addColumn(UserTable::COLUMN_CREATED_AT, 'datetime', ['notnull' => true]);
        $table->addColumn(UserTable::COLUMN_UPDATED_AT, 'datetime', ['notnull' => true]);

        $table->setPrimaryKey([UserTable::COLUMN_ID]);
        $table->addUniqueIndex([UserTable::COLUMN_EMAIL]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(UserTable::NAME);
    }
}
