<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Subscription extends Model
{
    public $id;
    public $stripe_id;
    public $customer_id;
    public $product_id;
    public $status;
    public $current_period_start;
    public $current_period_end;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('subscriptions');
        $this->belongsTo('customer_id', Customer::class, 'id', ['alias' => 'customer']);
        $this->belongsTo('product_id', Product::class, 'id', ['alias' => 'product']);
    }
}
