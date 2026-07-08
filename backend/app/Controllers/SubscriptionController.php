<?php

namespace App\Controllers;

use App\Services\SubscriptionService;
use Phalcon\Di\Injectable;

/** @property SubscriptionService $subscriptionService */
class SubscriptionController extends Injectable
{
    public function indexAction(): string
    {
        $subscriptions = $this->subscriptionService->getAll();

        return json_encode($subscriptions);
    }
}
