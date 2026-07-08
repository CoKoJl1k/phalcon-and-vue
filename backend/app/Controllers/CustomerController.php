<?php

namespace App\Controllers;

use App\Models\Customer;
use Phalcon\Di\Injectable;

class CustomerController extends Injectable
{
    public function indexAction(): string
    {
        $customers = Customer::find(['order' => 'name ASC']);
        $result = [];
        foreach ($customers as $c) {
            $result[] = ['id' => $c->id, 'name' => $c->name, 'email' => $c->email];
        }
        return json_encode($result);
    }
}
