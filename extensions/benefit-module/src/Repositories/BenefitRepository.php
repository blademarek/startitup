<?php

namespace Crm\BenefitModule\Repositories;

use Crm\ApplicationModule\Repository;
use DateTime;
use Exception;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Url;

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
        try {
            /** @var ActiveRow $benfit */
            $benfit = $this->insert([
                'title' => $title,
                'code' => $code,
                'valid_from' => new DateTime($validFrom),
                'valid_to' => new DateTime($validTo)
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $benfit;
    }
}
