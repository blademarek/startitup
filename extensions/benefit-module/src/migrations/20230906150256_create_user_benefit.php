<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateUserBenefit extends AbstractMigration
{
    // user_benefit Phinx migration
    const TABLE = 'user_benefit';

    public function up(): void
    {
        if (!$this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)
                ->addColumn('user_id', AdapterInterface::PHINX_TYPE_INTEGER, ['null' => false])
                ->addColumn('benefit_id', AdapterInterface::PHINX_TYPE_INTEGER, ['null' => false])

                ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
                ->addForeignKey('benefit_id', 'benefit', 'id', ['delete' => 'CASCADE'])

                ->create();
        }
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)->drop()->save();
        }
    }
}
