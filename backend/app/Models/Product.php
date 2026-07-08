<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Product extends Model
{
    public $id;
    public $stripe_id;
    public $name;
    public $description;
    public $amount;
    public $currency;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('products');
        $this->hasMany('id', Subscription::class, 'product_id', ['alias' => 'subscriptions']);
    }
}
