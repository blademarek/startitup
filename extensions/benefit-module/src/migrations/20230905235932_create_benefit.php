<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateBenefit extends AbstractMigration
{
    // benefit Phinx migration
    const TABLE = 'benefit';

    public function up(): void
    {
        if (!$this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)
                ->addColumn('title', AdapterInterface::PHINX_TYPE_STRING, ['null' => false])
                ->addColumn('code', AdapterInterface::PHINX_TYPE_STRING, ['null' => false])
                ->addColumn('image', AdapterInterface::PHINX_TYPE_STRING, ['null' => true])
                ->addColumn('valid_from', AdapterInterface::PHINX_TYPE_TIMESTAMP, ['null' => false])
                ->addColumn('valid_to', AdapterInterface::PHINX_TYPE_TIMESTAMP, ['null' => false])

                // unique indexes
                ->addIndex('code', ['unique' => true])

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
