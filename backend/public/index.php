<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Mvc\Micro;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new FactoryDefault();

$config = require __DIR__ . '/../app/Config/db.php';

$container->setShared('db', function () use ($config) {
    return new Postgresql($config['database']);
});

require __DIR__ . '/../app/Config/services.php';

$app = new Micro($container);

$app->before(function () {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
});

require_once __DIR__ . '/../app/Config/routes.php';

$app->handle($_SERVER['REQUEST_URI']);
