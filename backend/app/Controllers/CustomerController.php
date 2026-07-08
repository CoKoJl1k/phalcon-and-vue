<?php

namespace App\Controllers;

use App\Models\Customer;
use Phalcon\Di\Injectable;

class CustomerController extends Injectable
{
    public function indexAction(): string
    {
        $customers = Customer::find(['order' => 'name ASC']);

        return json_encode($customers);
    }
}
