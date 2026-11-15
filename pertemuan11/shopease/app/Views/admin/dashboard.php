<?php $this->setVar('title', 'Dasbor'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="stat-grid">
    <div class="stat"><div class="num"><?= $stats['products'] ?></div><div class="lbl">Produk</div></div>
    <div class="stat"><div class="num"><?= $stats['customers'] ?></div><div class="lbl">Pelanggan</div></div>
    <div class="stat"><div class="num"><?= $stats['orders'] ?></div><div class="lbl">Pesanan</div></div>
    <div class="stat"><div class="num"><?= $stats['transactions'] ?></div><div class="lbl">Transaksi terverifikasi</div></div>
    <div class="stat"><div class="num"><?= rupiah($stats['sales']) ?></div><div class="lbl">Total penjualan</div></div>
    <div class="stat">
        <div class="num"><?= $stats['awaiting_verification'] ?></div>
        <div class="lbl">Menunggu verifikasi pembayaran</div>
        <a class="caption" href="<?= site_url('admin/orders?status=waiting_verification') ?>">Tinjau</a>
    </div>
</div>

<h2 class="tagline">Stok menipis</h2>
<?php if ($lowStock === []): ?>
    <p class="muted">Semua stok aman.</p>
<?php else: ?>
    <div class="table-wrap">
        <table class="table mb-0">
            <thead><tr><th>Produk</th><th>Stok</th></tr></thead>
            <tbody>
            <?php foreach ($lowStock as $row): ?>
                <tr><td><?= esc($row['name']) ?></td><td><?= (int) $row['stock'] ?></td></tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
<?= $this->endSection() ?>
