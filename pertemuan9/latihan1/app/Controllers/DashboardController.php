<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $products = new ProductModel();

        return view('dashboard/index', [
            'title'         => 'Dashboard',
            'productCount'  => $products->countAllResults(),
            'totalStock'    => $products->selectSum('stock')->first()['stock'] ?? 0,
            'categoryCount' => (new CategoryModel())->countAllResults(),
            'userCount'     => is_admin() ? (new UserModel())->countAllResults() : null,
            'products'      => $products->withCategory()->orderBy('products.id', 'DESC')->findAll(5),
        ]);
    }
}
