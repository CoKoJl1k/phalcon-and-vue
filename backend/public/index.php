<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Mvc\Micro;
use App\Stripe\MockStripeClient;
use App\Services\SyncService;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SyncLog;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new FactoryDefault();

$container->setShared('db', function () {
    return new Postgresql([
        'host'     => getenv('DB_HOST'),
        'port'     => getenv('DB_PORT'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'dbname'   => getenv('DB_NAME'),
    ]);
});

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

$app->get('/api/health', function () {
    return json_encode(['status' => 'ok']);
});

$app->get('/api/subscriptions', function () use ($app) {
    $params = $app->request->getQuery();
    $conditions = [];
    $bind = [];

    if (!empty($params['status'])) {
        $conditions[] = 's.status = :status';
        $bind['status'] = $params['status'];
    }

    if (!empty($params['customer_id'])) {
        $conditions[] = 's.customer_id = :customer_id';
        $bind['customer_id'] = (int)$params['customer_id'];
    }

    if (!empty($params['search'])) {
        $conditions[] = 'LOWER(c.name) LIKE :search';
        $bind['search'] = '%' . mb_strtolower($params['search']) . '%';
    }

    $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

    $sql = "SELECT s.*, c.id AS c_id, c.name AS c_name, c.email AS c_email,
                   p.id AS p_id, p.name AS p_name, p.amount AS p_amount, p.currency AS p_currency
            FROM subscriptions s
            JOIN customers c ON c.id = s.customer_id
            JOIN products p ON p.id = s.product_id
            {$where}
            ORDER BY s.created_at DESC";

    $rows = $app->db->fetchAll($sql, \PDO::FETCH_ASSOC, $bind);

    $result = [];
    foreach ($rows as $row) {
        $result[] = [
            'id'            => (int)$row['id'],
            'stripe_id'     => $row['stripe_id'],
            'status'        => $row['status'],
            'customer'      => [
                'id'    => (int)$row['c_id'],
                'name'  => $row['c_name'],
                'email' => $row['c_email'],
            ],
            'product'       => [
                'id'       => (int)$row['p_id'],
                'name'     => $row['p_name'],
                'amount'   => (int)$row['p_amount'],
                'currency' => $row['p_currency'],
            ],
            'current_period_start' => $row['current_period_start'],
            'current_period_end'   => $row['current_period_end'],
            'created_at'    => $row['created_at'],
            'updated_at'    => $row['updated_at'],
        ];
    }

    return json_encode($result);
});

$app->get('/api/customers', function () {
    $customers = Customer::find(['order' => 'name ASC']);
    $result = [];
    foreach ($customers as $c) {
        $result[] = ['id' => $c->id, 'name' => $c->name, 'email' => $c->email];
    }
    return json_encode($result);
});

$app->get('/api/products', function () {
    $products = Product::find(['order' => 'name ASC']);
    $result = [];
    foreach ($products as $p) {
        $result[] = ['id' => $p->id, 'name' => $p->name, 'amount' => $p->amount, 'currency' => $p->currency];
    }
    return json_encode($result);
});

$app->post('/api/sync', function () use ($app) {
    $stripeClient = new MockStripeClient();
    $syncService = new SyncService($app->db, $stripeClient);
    $result = $syncService->run();

    $log = new SyncLog();
    $log->status = $result['success'] ? 'success' : 'error';
    $log->message = json_encode([
        'created' => $result['created'] ?? 0,
        'updated' => $result['updated'] ?? 0,
        'errors'  => $result['errors'] ?? [],
    ]);
    $log->save();

    return json_encode($result);
});

$app->get('/api/sync/last', function () {
    $log = SyncLog::findFirst(['order' => 'created_at DESC']);
    if (!$log) {
        return json_encode(null);
    }
    return json_encode([
        'id'         => $log->id,
        'status'     => $log->status,
        'message'    => json_decode($log->message, true),
        'created_at' => $log->created_at,
    ]);
});

$app->notFound(function () {
    http_response_code(404);
    return json_encode(['error' => 'Not found']);
});

$app->handle($_SERVER['REQUEST_URI']);
