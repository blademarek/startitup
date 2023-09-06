<?php

namespace Crm\BenefitModule;

use Contributte\Translation\Exceptions\InvalidArgument;
use Crm\ApplicationModule\CrmModule;
use Crm\ApplicationModule\Menu\MenuContainerInterface;
use Crm\ApplicationModule\Menu\MenuItem;

class BenefitModule extends CrmModule
{
    /**
     * @throws InvalidArgument
     */
    public function registerAdminMenuItems(MenuContainerInterface $menuContainer): void
    {
        $mainMenu = new MenuItem($this->translator->translate('benefit.menu.benefit'), ':Benefit:BenefitAdmin:default', 'fa fa-plus-square', 1000);

        $menuContainer->attachMenuItem($mainMenu);
    }
}
