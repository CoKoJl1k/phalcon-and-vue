<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Customer extends Model
{
    public $id;
    public $stripe_id;
    public $name;
    public $email;
    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('customers');
        $this->hasMany('id', Subscription::class, 'customer_id', ['alias' => 'subscriptions']);
    }
}
