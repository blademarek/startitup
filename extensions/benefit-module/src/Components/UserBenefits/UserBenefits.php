<?php

namespace Crm\BenefitModule\Components\UserBenefits;

use Crm\ApplicationModule\Widget\WidgetInterface;
use Crm\BenefitModule\Repositories\BenefitRepository;
use Crm\BenefitModule\Repositories\UserBenefitRepository;
use Nette\Application\UI\Control;
use Nette\Localization\Translator;

class UserBenefits extends Control implements WidgetInterface
{
    private string $templateName = 'user_benefits.latte';
    private $totalCount = null;

    public function __construct(
        private UserBenefitRepository $userBenefitRepository,
        private BenefitRepository $benefitRepository,
        private Translator $translator
    ) {
    }

    public function header($id = ''): string
    {
        // header name and count for user detail
        $header = $this->translator->translate('benefit.menu.benefit');
        if ($id) {
            $header .= ' <small>(' . $this->totalCount($id) . ')</small>';
        }

        return $header;
    }

    public function identifier(): string
    {
        return 'userbenefits';
    }

    public function render($id): void
    {
        // add data to the template
        $userBenefits = $this->userBenefitRepository->getUserBenefitIds($id);
        $this->template->userBenefits = $this->benefitRepository->getBenefitsById($userBenefits);

        $this->template->totalBenefits = $this->totalCount($id);

        $this->template->id = $id;
        $this->template->setFile(__DIR__ . '/' . $this->templateName);
        $this->template->render();
    }

    private function totalCount($id): int
    {
        if ($this->totalCount == null) {
            $this->totalCount = $this->userBenefitRepository->totalUserBenefits($id);
        }

        return $this->totalCount;
    }
}
