<?php

namespace App\Controllers;

use App\Models\Product;
use Phalcon\Di\Injectable;
use Phalcon\Http\ResponseInterface;

class ProductController extends Injectable
{
    public function indexAction(): ResponseInterface
    {
        $products = Product::find(['order' => 'name ASC']);

        return $this->response->setJsonContent($products);
    }
}
