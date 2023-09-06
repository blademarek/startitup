<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBenefit extends AbstractMigration
{
    const TABLE = 'benefit';

    public function up(): void
    {
        if (!$this->hasTable(self::TABLE)) {
            $this->table(self::TABLE)
                ->addColumn('title', 'string', ['null' => false])
                ->addColumn('code', 'string', ['null' => false])
                ->addColumn('image_url', 'string', ['null' => true])
                ->addColumn('valid_from', 'timestamp', ['null' => false])
                ->addColumn('valid_to', 'timestamp', ['null' => false])

                // unique indexes
                ->addIndex('code', ['unique' => true])

                // foreign keys
//                ->addForeignKey('user_id', 'users', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
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
