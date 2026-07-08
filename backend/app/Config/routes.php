<?php

/** @var \Phalcon\Mvc\Micro $app */

use Phalcon\Mvc\Micro\Collection;
use App\Controllers\SubscriptionController;
use App\Controllers\CustomerController;
use App\Controllers\ProductController;
use App\Controllers\SyncController;


$subscriptions = new Collection();
$subscriptions->setHandler(SubscriptionController::class, true);
$subscriptions->setPrefix('/api');
$subscriptions->get('/subscriptions', 'indexAction');
$app->mount($subscriptions);

$customers = new Collection();
$customers->setHandler(CustomerController::class, true);
$customers->setPrefix('/api');
$customers->get('/customers', 'indexAction');
$app->mount($customers);

$products = new Collection();
$products->setHandler(ProductController::class, true);
$products->setPrefix('/api');
$products->get('/products', 'indexAction');
$app->mount($products);

$sync = new Collection();
$sync->setHandler(SyncController::class, true);
$sync->setPrefix('/api');
$sync->post('/sync', 'syncAction');
$sync->get('/sync/last', 'lastAction');
$app->mount($sync);

$app->notFound(function () {
    http_response_code(404);
    return json_encode(['error' => 'Not found']);
});
