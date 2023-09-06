<?php

namespace Crm\BenefitModule\Forms;

use Crm\BenefitModule\Repositories\BenefitRepository;
use Exception;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Nette\Utils\ArrayHash;
use Tomaj\Form\Renderer\BootstrapRenderer;

class BenefitFactory
{
    public function __construct(
        private Translator $translator,
        private BenefitRepository $repository,
    ) {
    }

    public function create($benefitId): Form
    {
        // create nette form
        $defaults = [];

        if (isset($benefitId)) {
            $benefit = $this->repository->find($benefitId);
            $defaults = $benefit->toArray();
        }

        $form = new Form();

        $form->setRenderer(new BootstrapRenderer());
        $form->setTranslator($this->translator);
        $form->addProtection();

        $form->onSuccess[] = [$this, 'formSucceeded'];

        $form->addText('title', 'benefit.admin.form.title.label')
            ->setRequired('benefit.admin.form.title.required');

        $form->addText('code', 'benefit.admin.form.code.label')
            ->setRequired('benefit.admin.form.code.required');

        $form->addText('valid_from', 'benefit.admin.form.valid_from.label')
            ->setHtmlAttribute('class', 'flatpickr')
            ->setHtmlAttribute('flatpickr_datetime', '"1"')
            ->setRequired('benefit.admin.form.valid_from.required');

        $form->addText('valid_to', 'benefit.admin.form.valid_to.label')
            ->setHtmlAttribute('class', 'flatpickr')
            ->setHtmlAttribute('flatpickr_datetime', '"1"')
            ->setRequired('benefit.admin.form.valid_to.required');

        if ($benefitId) {
            $form->addHidden('benefit_id', $benefitId);
        }

        $form->addSubmit('send', $this->translator->translate('benefit.admin.actions.submit'))
            ->getControlPrototype()
            ->setName('button')
            ->setHtml('<i class="fa fa-save"></i> ' . $this->translator->translate('benefit.admin.actions.submit'));

        $form->setDefaults($defaults);
        $form->onSuccess[] = [$this, 'callback'];

        return $form;
    }

    /**
     * @throws Exception
     */
    public function formSucceeded($form, $values): void
    {
        // saving data after form validation
        $values = clone($values);
        foreach ($values as $i => $item) {
            if ($item instanceof ArrayHash) {
                unset($values[$i]);
            }
        }

        if (isset($values->benefit_id)) {
            $benefitId = $values->benefit_id;
            unset($values->benefit_id);

            $benefit = $this->repository->find($benefitId);
            $this->repository->update($benefit, $values);

            $this->onCallback = function () use ($form, $benefit) {
                $this->onUpdate->__invoke($form, $benefit);
            };
        } else {
            $benefit = $this->repository->add(
                $values->title,
                $values->code,
                $values->valid_from,
                $values->valid_to
            );

            $this->onCallback = function () use ($form, $benefit) {
                $this->onSave->__invoke($form, $benefit);
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
