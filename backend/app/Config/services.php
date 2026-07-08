<?php

/** @var \Phalcon\Di\FactoryDefault $container */

use App\Services\SyncService;
use App\Stripe\MockStripeClient;

$container->set('subscriptionService', function () use ($container) {
    $service = new \App\Services\SubscriptionService();
    $service->setDI($container);
    return $service;
});

$container->set('logService', function () use ($container) {
    $service = new \App\Services\LogService();
    $service->setDI($container);
    return $service;
});

$container->set('syncService', function () use ($container) {
    $stripeClient = new MockStripeClient();
    $service = new SyncService($container->get('db'), $stripeClient);
    $service->setDI($container);
    return $service;
});