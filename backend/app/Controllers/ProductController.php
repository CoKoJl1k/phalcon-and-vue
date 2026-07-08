<?php

namespace App\Controllers;

use App\Models\Product;
use Phalcon\Di\Injectable;

class ProductController extends Injectable
{
    public function indexAction(): string
    {
        $products = Product::find(['order' => 'name ASC']);

        return json_encode($products);
    }
}
