<?php

namespace App\Services;

use App\Stripe\StripeClientInterface;
use Phalcon\Db\Adapter\Pdo\Postgresql;

class SyncService
{
    private Postgresql $db;
    private StripeClientInterface $stripe;

    public function __construct(Postgresql $db, StripeClientInterface $stripe)
    {
        $this->db = $db;
        $this->stripe = $stripe;
    }

    public function run(): array
    {
        $created = 0;
        $updated = 0;
        $errors = [];

        try {
            $customers = $this->stripe->getCustomers();
            foreach ($customers as $c) {
                $this->upsertCustomer($c);
            }
        } catch (\Throwable $e) {
            $errors[] = 'Customer sync failed: ' . $e->getMessage();
        }

        try {
            $products = $this->stripe->getProducts();
            foreach ($products as $p) {
                $this->upsertProduct($p);
            }
        } catch (\Throwable $e) {
            $errors[] = 'Product sync failed: ' . $e->getMessage();
        }

        try {
            $subscriptions = $this->stripe->getSubscriptions();
            foreach ($subscriptions as $s) {
                $result = $this->upsertSubscription($s);
                $created += $result['created'] ? 1 : 0;
                $updated += $result['updated'] ? 1 : 0;
            }
        } catch (\Throwable $e) {
            $errors[] = 'Subscription sync failed: ' . $e->getMessage();
        }

        return [
            'success' => empty($errors),
            'created' => $created,
            'updated' => $updated,
            'errors'  => $errors,
        ];
    }

    private function upsertCustomer(array $data): void
    {
        $existing = $this->db->fetchOne(
            'SELECT id FROM customers WHERE stripe_id = :stripe_id',
            \PDO::FETCH_ASSOC,
            ['stripe_id' => $data['id']]
        );

        if ($existing) {
            $this->db->execute(
                'UPDATE customers SET name = :name, email = :email, updated_at = NOW() WHERE stripe_id = :stripe_id',
                ['name' => $data['name'], 'email' => $data['email'], 'stripe_id' => $data['id']]
            );
        } else {
            $this->db->execute(
                'INSERT INTO customers (stripe_id, name, email, created_at, updated_at) VALUES (:stripe_id, :name, :email, NOW(), NOW())',
                ['stripe_id' => $data['id'], 'name' => $data['name'], 'email' => $data['email']]
            );
        }
    }

    private function upsertProduct(array $data): void
    {
        $existing = $this->db->fetchOne(
            'SELECT id FROM products WHERE stripe_id = :stripe_id',
            \PDO::FETCH_ASSOC,
            ['stripe_id' => $data['id']]
        );

        if ($existing) {
            $this->db->execute(
                'UPDATE products SET name = :name, description = :description, amount = :amount, currency = :currency, updated_at = NOW() WHERE stripe_id = :stripe_id',
                ['name' => $data['name'], 'description' => $data['description'], 'amount' => $data['amount'], 'currency' => $data['currency'], 'stripe_id' => $data['id']]
            );
        } else {
            $this->db->execute(
                'INSERT INTO products (stripe_id, name, description, amount, currency, created_at, updated_at) VALUES (:stripe_id, :name, :description, :amount, :currency, NOW(), NOW())',
                ['stripe_id' => $data['id'], 'name' => $data['name'], 'description' => $data['description'], 'amount' => $data['amount'], 'currency' => $data['currency']]
            );
        }
    }

    private function upsertSubscription(array $data): array
    {
        $result = ['created' => false, 'updated' => false];

        $customer = $this->db->fetchOne(
            'SELECT id FROM customers WHERE stripe_id = :stripe_id',
            \PDO::FETCH_ASSOC,
            ['stripe_id' => $data['customer_id']]
        );

        $product = $this->db->fetchOne(
            'SELECT id FROM products WHERE stripe_id = :stripe_id',
            \PDO::FETCH_ASSOC,
            ['stripe_id' => $data['product_id']]
        );

        if (!$customer || !$product) {
            return $result;
        }

        $existing = $this->db->fetchOne(
            'SELECT id FROM subscriptions WHERE stripe_id = :stripe_id',
            \PDO::FETCH_ASSOC,
            ['stripe_id' => $data['id']]
        );

        if ($existing) {
            $this->db->execute(
                'UPDATE subscriptions SET customer_id = :customer_id, product_id = :product_id, status = :status, current_period_start = :period_start, current_period_end = :period_end, updated_at = NOW() WHERE stripe_id = :stripe_id',
                [
                    'customer_id'  => $customer['id'],
                    'product_id'   => $product['id'],
                    'status'       => $data['status'],
                    'period_start' => $data['period_start'],
                    'period_end'   => $data['period_end'],
                    'stripe_id'    => $data['id'],
                ]
            );
            $result['updated'] = true;
        } else {
            $this->db->execute(
                'INSERT INTO subscriptions (stripe_id, customer_id, product_id, status, current_period_start, current_period_end, created_at, updated_at) VALUES (:stripe_id, :customer_id, :product_id, :status, :period_start, :period_end, NOW(), NOW())',
                [
                    'stripe_id'    => $data['id'],
                    'customer_id'  => $customer['id'],
                    'product_id'   => $product['id'],
                    'status'       => $data['status'],
                    'period_start' => $data['period_start'],
                    'period_end'   => $data['period_end'],
                ]
            );
            $result['created'] = true;
        }

        return $result;
    }
}
