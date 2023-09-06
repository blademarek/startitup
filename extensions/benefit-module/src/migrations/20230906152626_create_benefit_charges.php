<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateBenefitCharges extends AbstractMigration
{
    // benefit_charges Phinx migration
    const TABLE = 'benefit_charges';

    public function up(): void
    {
        if (!$this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)
                ->addColumn('user_id', AdapterInterface::PHINX_TYPE_INTEGER, ['null' => false])
                ->addColumn('charges', AdapterInterface::PHINX_TYPE_INTEGER, ['null' => false])

                ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])

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
