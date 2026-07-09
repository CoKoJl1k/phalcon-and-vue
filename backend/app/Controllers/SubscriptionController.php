<?php

namespace App\Controllers;

use App\Services\SubscriptionService;
use Phalcon\Di\Injectable;
use Phalcon\Http\ResponseInterface;

/** @property SubscriptionService $subscriptionService */
class SubscriptionController extends Injectable
{
    public function indexAction(): ResponseInterface
    {
        $subscriptions = $this->subscriptionService->getAll();

        return $this->response->setJsonContent($subscriptions);
    }
}
