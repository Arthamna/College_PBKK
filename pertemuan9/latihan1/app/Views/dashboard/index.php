<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div><h1>Dashboard</h1><p>Selamat datang, <?= esc(session()->get('username')) ?>. Berikut ringkasan persediaanmu.</p></div>
    <a href="<?= site_url('products/create') ?>" class="btn btn-primary">+ Tambah Barang</a>
</div>
<?= $this->include('partials/alerts') ?>
<div class="stats-grid">
    <div class="card stat-card"><div class="card-body"><div class="stat-label">Jenis Barang</div><p class="stat-number"><?= number_format($productCount, 0, ',', '.') ?></p><p class="stat-caption">Barang terdaftar</p></div></div>
    <div class="card stat-card" style="--stat-color:#16a34a"><div class="card-body"><div class="stat-label">Total Stok</div><p class="stat-number"><?= number_format((float) $totalStock, 0, ',', '.') ?></p><p class="stat-caption">Unit tersedia</p></div></div>
    <div class="card stat-card" style="--stat-color:#d97706"><div class="card-body"><div class="stat-label">Kategori</div><p class="stat-number"><?= number_format($categoryCount, 0, ',', '.') ?></p><p class="stat-caption">Kelompok barang</p></div></div>
    <?php if (is_admin()): ?>
        <div class="card stat-card" style="--stat-color:#7c3aed"><div class="card-body"><div class="stat-label">Pengguna</div><p class="stat-number"><?= number_format($userCount, 0, ',', '.') ?></p><p class="stat-caption">Akun Admin dan Staff</p></div></div>
    <?php endif ?>
</div>
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2"><span>Barang Terbaru</span><a class="btn btn-sm btn-outline-primary" href="<?= site_url('products') ?>">Lihat Semua</a></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <caption class="visually-hidden">Lima barang yang terakhir ditambahkan</caption>
            <thead><tr><th class="ps-4">Nama Barang</th><th>Kategori</th><th>Stok</th><th>Harga</th><th class="pe-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr><td class="ps-4 fw-semibold"><?= esc($product['name']) ?></td><td><?= esc($product['category_name']) ?></td><td><?= (int) $product['stock'] ?> unit</td><td class="text-nowrap"><?= rupiah($product['price']) ?></td><td class="pe-4"><a class="btn btn-sm btn-outline-primary" href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a></td></tr>
            <?php endforeach ?>
            <?php if ($products === []): ?><tr><td colspan="5" class="empty-state">Belum ada barang. Tambahkan barang pertamamu.</td></tr><?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
