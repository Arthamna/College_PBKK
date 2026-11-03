<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div><h1>Data Barang</h1><p>Kelola barang, stok, harga, dan foto dalam satu tempat.</p></div>
    <a href="<?= site_url('products/create') ?>" class="btn btn-primary">+ Tambah Barang</a>
</div>
<?= $this->include('partials/alerts') ?>
<div class="card">
    <div class="card-body border-bottom">
        <form method="get" action="<?= site_url('products') ?>" class="filter-form">
            <div class="search-field"><label for="q" class="visually-hidden">Cari nama barang</label><input class="form-control" type="search" name="q" id="q" placeholder="Cari nama barang..." value="<?= esc($search) ?>"></div>
            <div class="category-field"><label for="category_id" class="visually-hidden">Filter kategori</label><select class="form-select" name="category_id" id="category_id"><option value="">Semua kategori</option><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>" <?= (string) $category['id'] === $categoryId ? 'selected' : '' ?>><?= esc($category['name']) ?></option><?php endforeach ?></select></div>
            <button class="btn btn-primary" type="submit">Cari</button>
            <a class="btn btn-outline-secondary" href="<?= site_url('products') ?>">Reset</a>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <caption class="visually-hidden">Daftar barang persediaan</caption>
            <thead><tr><th class="ps-4">Foto</th><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Harga</th><th class="pe-4">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="ps-4"><?php if ($product['image']): ?><img class="product-thumb" src="<?= site_url('products/' . $product['id'] . '/image') ?>" alt="Foto <?= esc($product['name']) ?>" loading="lazy"><?php else: ?><span class="image-placeholder" role="img" aria-label="Tanpa foto">▣</span><?php endif ?></td>
                    <td class="fw-semibold"><?= esc($product['name']) ?></td>
                    <td><?= esc($product['category_name']) ?></td>
                    <td><span class="badge <?= (int) $product['stock'] === 0 ? 'bg-danger' : 'bg-success' ?>"><?= (int) $product['stock'] ?> unit</span></td>
                    <td class="text-nowrap"><?= rupiah($product['price']) ?></td>
                    <td class="pe-4"><div class="action-buttons">
                        <a class="btn btn-sm btn-outline-primary" href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a>
                        <?php if (is_admin()): ?><form method="post" action="<?= site_url('products/' . $product['id'] . '/delete') ?>" data-confirm="Hapus barang ini beserta fotonya?"><?= csrf_field() ?><button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button></form><?php endif ?>
                    </div></td>
                </tr>
            <?php endforeach ?>
            <?php if ($products === []): ?><tr><td colspan="6" class="empty-state">Tidak ada barang yang ditemukan.</td></tr><?php endif ?>
            </tbody>
        </table>
    </div>
    <div class="card-body border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
        <span class="small text-muted">Total <?= $pager->getTotal() ?> barang</span>
        <?= $pager->only(['q', 'category_id'])->links('default', 'inventory') ?>
    </div>
</div>
<?= $this->endSection() ?>
