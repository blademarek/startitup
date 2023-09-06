<?php

namespace Crm\BenefitModule\Presenters;

use Crm\ApplicationModule\Presenters\FrontendPresenter;
use Crm\BenefitModule\Forms\ChangeBenefitFactory;
use Crm\BenefitModule\Repositories\BenefitRepository;
use Crm\UsersModule\Repository\UsersRepository;
use Nette\Application\UI\Form;

class ChooseUserBenefitPresenter extends FrontendPresenter
{
    public function __construct(
        private ChangeBenefitFactory $factory,
        private BenefitRepository $benefitRepository,
        UsersRepository $usersRepository
    ) {
        parent::__construct();
    }


    public function renderChangeBenefit(): void
    {
        // render user benefit change template
        $this->onlyLoggedIn();

        $userBenefits = $this->usersRepository->find($this->getUser()->id)->related('user_benefit')->fetchPairs(null, 'benefit_id');
        $selectedUserBenefits = $this->benefitRepository->getBenefitsById($userBenefits);
        $this->template->selectedBenefits = $selectedUserBenefits;
    }

    public function createComponentChangeBenefitForm(): Form
    {
        // render user benefit change form
        $this->onlyLoggedIn();

        $userBenefits = $this->usersRepository->find($this->getUser()->id)->related('user_benefit')->fetchAll();

        $this->template->userBenefits = $userBenefits;

        $form = $this->factory->create($this->getUser()->id);
        $this->factory->onUpdate = function () {
            $this->flashMessage($this->translator->translate('benefit.user.actions.benefit_saved'));
            $this->redirect(':Benefit:ChooseUserBenefit:changeBenefit');
        };
        return $form;
    }
}
