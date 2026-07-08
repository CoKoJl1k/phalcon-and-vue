<?php

namespace App\Controllers;

use App\Models\Product;
use Phalcon\Di\Injectable;

class ProductController extends Injectable
{
    public function indexAction(): string
    {
        $products = Product::find(['order' => 'name ASC']);
        $result = [];
        foreach ($products as $p) {
            $result[] = ['id' => $p->id, 'name' => $p->name, 'amount' => $p->amount, 'currency' => $p->currency];
        }
        return json_encode($result);
    }
}
