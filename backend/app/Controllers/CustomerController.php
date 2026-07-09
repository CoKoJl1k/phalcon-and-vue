<?php

namespace App\Controllers;

use App\Models\Customer;
use Phalcon\Di\Injectable;
use Phalcon\Http\ResponseInterface;

class CustomerController extends Injectable
{
    public function indexAction(): ResponseInterface
    {
        $customers = Customer::find(['order' => 'name ASC']);

        return $this->response->setJsonContent($customers);
    }
}
