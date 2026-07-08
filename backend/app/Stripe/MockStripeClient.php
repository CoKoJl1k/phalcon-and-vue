<?php

namespace App\Stripe;

class MockStripeClient implements StripeClientInterface
{
    private static array $customers = [
        ['id' => 'cus_001', 'name' => 'Acme Corp',        'email' => 'acme@acme.com'],
        ['id' => 'cus_002', 'name' => 'Globex Inc',       'email' => 'contact@globex.com'],
        ['id' => 'cus_003', 'name' => 'Initech',          'email' => 'info@initech.com'],
        ['id' => 'cus_004', 'name' => 'Umbrella Corp',    'email' => 'admin@umbrella.com'],
        ['id' => 'cus_005', 'name' => 'Wayne Enterprises','email' => 'bruce@wayne.com'],
    ];

    private static array $products = [
        ['id' => 'prod_001', 'name' => 'Starter',      'description' => 'Basic features for small teams',  'amount' => 500,   'currency' => 'usd'],
        ['id' => 'prod_002', 'name' => 'Basic',        'description' => 'Essential tools for growing business', 'amount' => 1000,  'currency' => 'usd'],
        ['id' => 'prod_003', 'name' => 'Pro',          'description' => 'Advanced features and analytics', 'amount' => 3000,  'currency' => 'usd'],
        ['id' => 'prod_004', 'name' => 'Enterprise',   'description' => 'Full suite with dedicated support', 'amount' => 10000, 'currency' => 'usd'],
    ];

    public function getCustomers(): array
    {
        return self::$customers;
    }

    public function getProducts(): array
    {
        return self::$products;
    }

    public function getSubscriptions(): array
    {
        $base = strtotime('2026-01-01');

        return [
            [
                'id' => 'sub_001',
                'customer_id' => 'cus_001',
                'product_id'  => 'prod_003',
                'status'      => 'active',
                'period_start'=> date('c', $base),
                'period_end'  => date('c', $base + 86400 * 30),
            ],
            [
                'id' => 'sub_002',
                'customer_id' => 'cus_001',
                'product_id'  => 'prod_001',
                'status'      => 'active',
                'period_start'=> date('c', $base - 86400 * 10),
                'period_end'  => date('c', $base - 86400 * 10 + 86400 * 30),
            ],
            [
                'id' => 'sub_003',
                'customer_id' => 'cus_002',
                'product_id'  => 'prod_004',
                'status'      => 'past_due',
                'period_start'=> date('c', $base - 86400 * 5),
                'period_end'  => date('c', $base - 86400 * 5 + 86400 * 30),
            ],
            [
                'id' => 'sub_004',
                'customer_id' => 'cus_002',
                'product_id'  => 'prod_002',
                'status'      => 'canceled',
                'period_start'=> date('c', $base - 86400 * 60),
                'period_end'  => date('c', $base - 86400 * 30),
            ],
            [
                'id' => 'sub_005',
                'customer_id' => 'cus_003',
                'product_id'  => 'prod_002',
                'status'      => 'trialing',
                'period_start'=> date('c', $base),
                'period_end'  => date('c', $base + 86400 * 14),
            ],
            [
                'id' => 'sub_006',
                'customer_id' => 'cus_003',
                'product_id'  => 'prod_003',
                'status'      => 'active',
                'period_start'=> date('c', $base - 86400 * 20),
                'period_end'  => date('c', $base - 86400 * 20 + 86400 * 30),
            ],
            [
                'id' => 'sub_007',
                'customer_id' => 'cus_004',
                'product_id'  => 'prod_001',
                'status'      => 'unpaid',
                'period_start'=> date('c', $base - 86400 * 2),
                'period_end'  => date('c', $base - 86400 * 2 + 86400 * 30),
            ],
            [
                'id' => 'sub_008',
                'customer_id' => 'cus_004',
                'product_id'  => 'prod_004',
                'status'      => 'active',
                'period_start'=> date('c', $base - 86400 * 15),
                'period_end'  => date('c', $base - 86400 * 15 + 86400 * 30),
            ],
            [
                'id' => 'sub_009',
                'customer_id' => 'cus_005',
                'product_id'  => 'prod_003',
                'status'      => 'active',
                'period_start'=> date('c', $base),
                'period_end'  => date('c', $base + 86400 * 30),
            ],
            [
                'id' => 'sub_010',
                'customer_id' => 'cus_005',
                'product_id'  => 'prod_004',
                'status'      => 'canceled',
                'period_start'=> date('c', $base - 86400 * 90),
                'period_end'  => date('c', $base - 86400 * 60),
            ],
        ];
    }
}
