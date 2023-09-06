<?php

namespace Crm\BenefitModule\Repositories;

use Crm\ApplicationModule\Repository;
use Exception;
use Nette\Database\Explorer;

class BenefitChargesRepository extends Repository
{
    protected $tableName = 'benefit_charges';

    public function __construct(
        Explorer $database
    ) {
        parent::__construct($database);
    }

    /**
     * @throws Exception
     */
    public function addCharge(int $userId): void
    {
        // add charge for specific user
        $benefitCharges = $this->findBy('user_id', $userId);

        if (!$benefitCharges) {
            $this->insert([
                'user_id' => $userId,
                'charges' => 1
            ]);

            return;
        }

        $this->update($benefitCharges, [
            'charges' => $benefitCharges->charges + 1
        ]);
    }

    /**
     * @throws Exception
     */
    public function removeCharge(int $userId): void
    {
        // remove charge for specific user
        $benefitCharges = $this->findBy('user_id', $userId);

        if (!$benefitCharges) {
            return;
        }

        if ($benefitCharges->charges === 1) {
            $this->delete($benefitCharges);

            return;
        }

        $this->update($benefitCharges, [
            'charges' => $benefitCharges->charges - 1
        ]);
    }

    public function getUserCharges(int $userId): int
    {
        // get count of user charges
        $userCharges = $this->getTable()->where('user_id', $userId)
            ->fetch();

        return $userCharges?->charges ?? 0;
    }
}
