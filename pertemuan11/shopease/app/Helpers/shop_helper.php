<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;

if (! function_exists('rupiah')) {
    function rupiah(int|float|string $amount): string
    {
        return 'Rp' . number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('current_user')) {
    /**
     * @return array{id:int,name:string,role:Role}|null
     */
    function current_user(): ?array
    {
        $id   = session()->get('user_id');
        $role = Role::tryFrom((string) session()->get('role'));

        if (! is_int($id) || $role === null) {
            return null;
        }

        return ['id' => $id, 'name' => (string) session()->get('name'), 'role' => $role];
    }
}

if (! function_exists('cart_count')) {
    function cart_count(): int
    {
        $user = current_user();

        if ($user === null || $user['role'] !== Role::Customer) {
            return 0;
        }

        return \Config\Services::cart()->summary($user['id'])->itemCount();
    }
}

if (! function_exists('status_chip')) {
    function status_chip(OrderStatus|PaymentStatus $status): string
    {
        $tone = match ($status) {
            OrderStatus::Completed, PaymentStatus::Verified => 'ok',
            OrderStatus::Cancelled, PaymentStatus::Rejected => 'bad',
            default => 'wait',
        };

        return '<span class="chip chip-' . $tone . '">' . esc($status->label()) . '</span>';
    }
}

if (! function_exists('product_initial')) {
    function product_initial(string $name): string
    {
        return mb_strtoupper(mb_substr(trim($name), 0, 1));
    }
}
