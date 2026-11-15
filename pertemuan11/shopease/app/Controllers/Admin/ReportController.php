<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Enums\OrderStatus;
use Config\Services;

class ReportController extends BaseController
{
    public function index(): string
    {
        $from = $this->date('from', date('Y-m-01'));
        $to   = $this->date('to', date('Y-m-d'));

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $status = OrderStatus::tryFrom((string) $this->request->getGet('status'));

        return view('admin/report', [
            'report'  => Services::reports()->salesReport($from, $to, $status),
            'from'    => $from,
            'to'      => $to,
            'current' => $status,
        ]);
    }

    private function date(string $key, string $default): string
    {
        $value = (string) $this->request->getGet($key);
        $date  = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value ? $value : $default;
    }
}
