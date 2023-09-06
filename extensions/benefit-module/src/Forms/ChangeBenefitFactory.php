<?php

namespace Crm\BenefitModule\Forms;

use Crm\BenefitModule\Repositories\BenefitChargesRepository;
use Crm\BenefitModule\Repositories\BenefitRepository;
use Crm\BenefitModule\Repositories\UserBenefitRepository;
use Crm\UsersModule\Repository\UsersRepository;
use Exception;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Tomaj\Form\Renderer\BootstrapRenderer;

class ChangeBenefitFactory
{
    public function __construct(
        private BenefitChargesRepository $benefitChargesRepository,
        private Translator $translator,
        private BenefitRepository $benefitRepository,
        private UserBenefitRepository $userBenefitRepository,
        private UsersRepository $usersRepository
    ) {
    }

    public function create(int $userId): Form
    {
        // create nette form
        $benefitCharges = $this->benefitChargesRepository->getUserCharges($userId);
        $form = new Form();

        $form->setRenderer(new BootstrapRenderer());
        $form->setTranslator($this->translator);
        $form->addProtection();

        $form->onSuccess[] = [$this, 'formSucceeded'];

        $activeBenefits = $this->benefitRepository->getActiveBenefits();
        $userSelectedBenefits = $this->usersRepository->find($userId)->related('user_benefit')->fetchPairs(null, 'benefit_id');
        $disableConfig = $benefitCharges === 0;

        if (!$disableConfig && !empty($userSelectedBenefits)) {
            $disableConfig = $userSelectedBenefits;
        }

        $form->addRadioList('benefits', $this->translator->translate('benefit.menu.benefit'), $activeBenefits)
            ->setDisabled($disableConfig);

        if ($userId) {
            $form->addHidden('user_id', $userId);
        }

        $availableBenefits = array_diff(array_keys($activeBenefits), $userSelectedBenefits);
        if ($benefitCharges && $availableBenefits) {
            $form->addSubmit('send', $this->translator->translate('benefit.admin.actions.submit'))
                ->getControlPrototype()
                ->setName('button')
                ->setHtml('<i class="fa fa-save"></i> ' . $this->translator->translate('benefit.admin.actions.submit'));

            $form->onSuccess[] = [$this, 'callback'];
        }

        return $form;
    }

    /**
     * @throws Exception
     */
    public function formSucceeded($form, $values): void
    {
        // saving data after form validation
        $benefits = $values->benefits;
        unset($values->benefits);

        if (isset($benefits)) {
            $benefit = $this->userBenefitRepository->add((int)$values->user_id, $benefits);
            $this->benefitChargesRepository->removeCharge($values->user_id);

            $this->onCallback = function () use ($form, $benefit) {
                $this->onUpdate->__invoke($form, $benefit);
            };
        }
    }

    public function callback(): void
    {
        // invoking callback after changes are saved

        if (isset($this->onCallback)) {
            $this->onCallback->__invoke();
        }
    }
}
