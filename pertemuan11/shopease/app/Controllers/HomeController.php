<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $products = model(ProductModel::class);

        return view('shop/home', [
            'categories'  => model(CategoryModel::class)->withProductCount(),
            'latest'      => $products->latest(4),
            'bestSellers' => $products->bestSellers(4),
        ]);
    }
}
