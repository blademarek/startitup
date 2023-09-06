<?php

namespace Crm\BenefitModule\Presenters;

use Crm\AdminModule\Presenters\AdminPresenter;
use Crm\BenefitModule\Forms\BenefitFactory;
use Crm\BenefitModule\Repositories\BenefitRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

class BenefitAdminPresenter extends AdminPresenter
{
    public function __construct(
        private BenefitFactory    $factory,
        private BenefitRepository $repository
    ) {
        parent::__construct();
    }

    public function renderDefault(): void
    {
        $benefits = $this->repository->getTable()->fetchAll();

        $this->template->benefits = $benefits;
        $this->template->totalCount = count($benefits);
    }

    /**
     * @throws BadRequestException
     */
    public function renderShow($id): void
    {
        $benefit = $this->repository->find($id);
        if (!$benefit) {
            throw new BadRequestException();
        }
        $this->template->benefit = $benefit;
        $this->template->translator = $this->translator;
    }

    public function renderNew()
    {
    }

    /**
     * @throws BadRequestException
     */
    public function renderEdit($id): void
    {
        $benefit = $this->repository->find($id);
        if (!$benefit) {
            throw new BadRequestException();
        }
        $this->template->benefit = $benefit;
    }

    public function createComponentBenefitForm(): Form
    {
        $id = null;
        if (isset($this->params['id'])) {
            $id = $this->params['id'];
        }

        $form = $this->factory->create($id);
        $this->factory->onSave = function ($form, $benefit) {
            $this->flashMessage($this->translator->translate('benefit.admin.actions.created'));
            $this->redirect('BenefitAdmin:Show', $benefit->id);
        };
        $this->factory->onUpdate = function ($form, $benefit) {
            $this->flashMessage($this->translator->translate('benefit.admin.actions.updated'));
            $this->redirect('BenefitAdmin:Show', $benefit->id);
        };
        return $form;
    }
}
