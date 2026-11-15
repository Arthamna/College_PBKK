<?php $this->setVar('title', 'Pelanggan'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="get" class="row g-2" style="margin-bottom:24px" role="search">
    <div class="col-12 col-md-5"><input class="form-control search-input" type="search" name="q" value="<?= esc($q) ?>" placeholder="Cari nama atau email" maxlength="100" aria-label="Cari pelanggan"></div>
    <div class="col-auto"><button class="btn-pill" type="submit">Cari</button></div>
</form>
<div class="table-wrap">
    <table class="table mb-0">
        <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Pesanan</th><th>Bergabung</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= esc($customer['name']) ?></td>
                <td><?= esc($customer['email']) ?></td>
                <td><?= esc((string) $customer['phone']) ?></td>
                <td><?= (int) $customer['order_count'] ?></td>
                <td><?= esc(date('d M Y', strtotime((string) $customer['created_at']))) ?></td>
                <td><?= (int) $customer['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
            </tr>
        <?php endforeach ?>
        <?php if ($customers === []): ?><tr><td colspan="6" class="muted">Tidak ada data.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
