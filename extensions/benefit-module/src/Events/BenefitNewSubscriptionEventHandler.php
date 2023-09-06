<?php

namespace Crm\BenefitModule\Events;

use Crm\BenefitModule\Repositories\BenefitChargesRepository;
use Crm\SubscriptionsModule\Events\NewSubscriptionEvent;
use Exception;
use League\Event\AbstractListener;
use League\Event\EventInterface;
use Tracy\Debugger;
use Tracy\ILogger;

class BenefitNewSubscriptionEventHandler extends AbstractListener
{
    public function __construct(
        private BenefitChargesRepository $benefitChargesRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(EventInterface $event): void
    {
        // handling new subscription process - adding charge to user
        /** @var NewSubscriptionEvent $event */
        if (!$event instanceof NewSubscriptionEvent) {
            Debugger::log(
                'Received invalid event in BenefitNewSubscriptionEventHandler: '
                . get_class($event),
                ILogger::ERROR
            );
            return;
        }

        $subscription = $event->getSubscription();

        if ($subscription->user_id) {
            $this->benefitChargesRepository->addCharge($subscription->user_id);
        }
    }
}
