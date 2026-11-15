<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class CustomerController extends BaseController
{
    public function index(): string
    {
        $q = trim((string) $this->request->getGet('q'));

        return view('admin/customers', [
            'customers' => model(UserModel::class)->customersWithOrderCount($q !== '' ? mb_substr($q, 0, 100) : null),
            'q'         => $q,
        ]);
    }
}
