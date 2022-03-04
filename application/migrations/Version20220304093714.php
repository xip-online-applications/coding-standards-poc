<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use XIP\User\Infrastructure\Repository\Database\RoleTable;
use XIP\User\Infrastructure\Repository\Database\RoleUserPivot;
use XIP\User\Infrastructure\Repository\Database\UserTable;

final class Version20220304093714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create role user pivot';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(RoleUserPivot::NAME);

        $table->addColumn(RoleUserPivot::COLUMN_ROLE_ID, 'integer', ['autoincrement' => true]);
        $table->addForeignKeyConstraint(RoleTable::NAME, [RoleUserPivot::COLUMN_ROLE_ID], [RoleTable::COLUMN_ID], ['onDelete' => 'cascade']);

        $table->addColumn(RoleUserPivot::COLUMN_USER_ID, 'integer', ['notnull' => true]);
        $table->addForeignKeyConstraint(UserTable::NAME, [RoleUserPivot::COLUMN_USER_ID], [UserTable::COLUMN_ID], ['onDelete' => 'cascade']);

        $table->setPrimaryKey([RoleUserPivot::COLUMN_ROLE_ID, RoleUserPivot::COLUMN_USER_ID]);
    }
    
    public function down(Schema $schema): void
    {
        $schema->dropTable(RoleUserPivot::class);
    }
}
