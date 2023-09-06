<?php

namespace Crm\BenefitModule\Repositories;

use Crm\ApplicationModule\Repository;
use Exception;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

class UserBenefitRepository extends Repository
{
    protected $tableName = 'user_benefit';

    public function __construct(
        Explorer $database
    ) {
        parent::__construct($database);
    }

    /**
     * @throws Exception
     */
    public function add(
        int $userId,
        int $benefitId
    ): ActiveRow {
        // add Benefit to user
        try {
            $userBenefit = $this->insert([
                'user_id' => $userId,
                'benefit_id' => $benefitId
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $userBenefit;
    }

    public function totalUserBenefits(int $userId): int
    {
        // get count of user benefits
        return $this->getTable()->where(['user_id' => $userId])->count('*');
    }

    public function getUserBenefitIds(int $userId): array
    {
        // get user benefits by id
        return $this->getTable()->where(['user_id' => $userId])->fetchPairs(null, 'benefit_id');
    }
}
