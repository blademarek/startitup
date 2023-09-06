<?php

namespace Crm\BenefitModule\Repositories;

use Crm\ApplicationModule\Repository;
use DateTime;
use Exception;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

class BenefitRepository extends Repository
{
    protected $tableName = 'benefit';

    public function __construct(
        Explorer $database
    ) {
        parent::__construct($database);
    }

    /**
     * @throws Exception
     */
    public function add(
        string $title,
        string $code,
        string $validFrom,
        string $validTo
    ): ActiveRow {
        // add benefit
        try {
            $benefit = $this->insert([
                'title' => $title,
                'code' => $code,
                'valid_from' => new DateTime($validFrom),
                'valid_to' => new DateTime($validTo)
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $benefit;
    }

    public function getActiveBenefits(): array
    {
        // get filtered benefits that are active
        $now = new DateTime();

        return $this->getTable()
            ->where(['valid_from <=' => $now])
            ->where(['valid_to >=' => $now])
            ->fetchPairs('id', 'title');
    }

    public function getBenefitsById(array $benefits): array
    {
        // get benefits by their id
        return $this->getTable()
            ->where('id', $benefits)
            ->fetchAll();
    }
}
