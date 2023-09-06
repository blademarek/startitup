<?php

namespace Crm\BenefitModule;

use Contributte\Translation\Exceptions\InvalidArgument;
use Crm\ApiModule\Api\ApiRoutersContainerInterface;
use Crm\ApiModule\Router\ApiIdentifier;
use Crm\ApiModule\Router\ApiRoute;
use Crm\ApplicationModule\CrmModule;
use Crm\ApplicationModule\Menu\MenuContainerInterface;
use Crm\ApplicationModule\Menu\MenuItem;
use Crm\ApplicationModule\Widget\LazyWidgetManagerInterface;
use Crm\BenefitModule\Api\BenefitsHandler;
use Crm\BenefitModule\Components\UserBenefits\UserBenefits;
use Crm\BenefitModule\Events\BenefitNewSubscriptionEventHandler;
use Crm\SubscriptionsModule\Events\NewSubscriptionEvent;
use Crm\UsersModule\Auth\UserTokenAuthorization;
use League\Event\Emitter;

class BenefitModule extends CrmModule
{
    public function registerApiCalls(ApiRoutersContainerInterface $apiRoutersContainer): void
    {
        // register API handler
        $apiRoutersContainer->attachRouter(
            new ApiRoute(
                new ApiIdentifier('1', 'user-benefit', 'list'),
                BenefitsHandler::class,
                UserTokenAuthorization::class
            )
        );
    }

    /**
     * @throws InvalidArgument
     */
    public function registerAdminMenuItems(MenuContainerInterface $menuContainer): void
    {
        // add admin menu
        $mainMenu = new MenuItem($this->translator->translate('benefit.menu.benefit'), ':Benefit:BenefitAdmin:default', 'fa fa-plus-square', 781);

        $menuContainer->attachMenuItem($mainMenu);
    }

    /**
     * @throws InvalidArgument
     */
    public function registerFrontendMenuItems(MenuContainerInterface $menuContainer): void
    {
        // add user menu
        $menuItem = new MenuItem(
            $this->translator->translate('benefit.menu.benefit'),
            ':Benefit:ChooseUserBenefit:changeBenefit',
            '',
            500,
            true
        );
        $menuContainer->attachMenuItem($menuItem);
    }

    public function registerEventHandlers(Emitter $emitter): void
    {
        // register handlers for events
        $emitter->addListener(
            NewSubscriptionEvent::class,
            $this->getInstance(BenefitNewSubscriptionEventHandler::class)
        );
    }

    public function registerLazyWidgets(LazyWidgetManagerInterface $lazyWidgetManager): void
    {
        // register widget used in admin user detail
        $lazyWidgetManager->registerWidget(
            'admin.user.detail.bottom',
            UserBenefits::class,
            101
        );
    }
}
