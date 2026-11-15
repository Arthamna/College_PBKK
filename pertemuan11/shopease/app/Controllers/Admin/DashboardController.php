<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $reports = Services::reports();

        return view('admin/dashboard', ['stats' => $reports->dashboard(), 'lowStock' => $reports->lowStock()]);
    }
}
