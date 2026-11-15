<?php use App\Enums\OrderStatus; use App\Enums\PaymentStatus; $this->setVar('title', 'Pesanan'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px">
    <a class="chip-option <?= $current === null ? 'selected' : '' ?>" href="<?= site_url('admin/orders') ?>">Semua</a>
    <?php foreach (OrderStatus::cases() as $case): ?>
        <a class="chip-option <?= $current === $case ? 'selected' : '' ?>" href="<?= site_url('admin/orders?status=' . $case->value) ?>"><?= esc($case->label()) ?></a>
    <?php endforeach ?>
</div>
<div class="table-wrap">
    <table class="table mb-0">
        <thead><tr><th>No. pesanan</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Pembayaran</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= esc($order['order_number']) ?></td>
                <td><?= esc($order['customer_name']) ?></td>
                <td><?= esc(date('d M Y H:i', strtotime((string) $order['created_at']))) ?></td>
                <td><?= rupiah($order['total']) ?></td>
                <td><?= status_chip(PaymentStatus::from($order['payment_status'] ?? 'unpaid')) ?></td>
                <td><?= status_chip(OrderStatus::from($order['status'])) ?></td>
                <td><a href="<?= site_url('admin/orders/' . $order['id']) ?>">Detail</a></td>
            </tr>
        <?php endforeach ?>
        <?php if ($orders === []): ?><tr><td colspan="7" class="muted">Tidak ada pesanan.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $pager->links('default', 'shop') ?>
<?= $this->endSection() ?>
