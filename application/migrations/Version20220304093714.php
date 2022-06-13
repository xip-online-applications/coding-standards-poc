<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use XIP\User\Infrastructure\Repository\Database\RoleTable;
use XIP\User\Infrastructure\Repository\Database\UserRolePivot;
use XIP\User\Infrastructure\Repository\Database\UserTable;

final class Version20220304093714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create role user pivot';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable(UserRolePivot::NAME);

        $table->addColumn(UserRolePivot::COLUMN_USER_ID, 'integer', ['notnull' => true]);
        $table->addForeignKeyConstraint(
            UserTable::NAME,
            [UserRolePivot::COLUMN_USER_ID],
            [UserTable::COLUMN_ID],
            ['onDelete' => 'cascade']
        );
        
        $table->addColumn(UserRolePivot::COLUMN_ROLE_ID, 'integer', ['notnull' => true]);
        $table->addForeignKeyConstraint(
            RoleTable::NAME,
            [UserRolePivot::COLUMN_ROLE_ID],
            [RoleTable::COLUMN_ID],
            ['onDelete' => 'cascade']
        );
        
        $table->setPrimaryKey([UserRolePivot::COLUMN_USER_ID, UserRolePivot::COLUMN_ROLE_ID]);
    }
    
    public function down(Schema $schema): void
    {
        $schema->dropTable(UserRolePivot::class);
    }
}
