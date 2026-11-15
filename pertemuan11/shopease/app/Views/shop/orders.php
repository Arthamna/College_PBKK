<?php
use App\Enums\OrderStatus;

$this->setVar('title', 'Pesanan saya');
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <h1 class="display-md">Pesanan saya</h1>
    <?php if ($orders === []): ?>
        <div class="utility-card"><p>Belum ada pesanan.</p><a class="btn-pill" href="<?= site_url('products') ?>">Mulai belanja</a></div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table mb-0">
                <thead><tr><th>No. pesanan</th><th>Tanggal</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= esc($order['order_number']) ?></td>
                        <td><?= esc(date('d M Y H:i', strtotime((string) $order['created_at']))) ?></td>
                        <td><?= rupiah($order['total']) ?></td>
                        <td><?= status_chip(OrderStatus::from($order['status'])) ?></td>
                        <td><a href="<?= site_url('orders/' . $order['id']) ?>">Detail</a></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?= $pager->links('default', 'shop') ?>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
