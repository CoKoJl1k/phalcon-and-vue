<?php

namespace App\Services;

use Phalcon\Di\Injectable;

/** @property \Phalcon\Db\Adapter\Pdo\Postgresql $db */
class SubscriptionService extends Injectable
{
    public function getAll(): array
    {
        $sql = "SELECT s.*, c.id AS c_id, c.name AS c_name, c.email AS c_email,
                       p.id AS p_id, p.name AS p_name, p.amount AS p_amount, p.currency AS p_currency
                FROM subscriptions s
                JOIN customers c ON c.id = s.customer_id
                JOIN products p ON p.id = s.product_id
                ORDER BY s.created_at DESC";

        $rows = $this->db->fetchAll($sql, \PDO::FETCH_ASSOC);

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

        return $result;
    }
}
