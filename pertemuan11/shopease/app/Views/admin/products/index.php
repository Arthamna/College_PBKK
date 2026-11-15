<?php use App\Enums\ProductStatus; $this->setVar('title', 'Produk'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php if ($isAdmin): ?>
    <div class="page-head"><span></span><a class="btn-pill btn-sm-pill" href="<?= site_url('admin/products/new') ?>">Tambah produk</a></div>
<?php endif ?>
<div class="table-wrap">
    <table class="table mb-0">
        <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= esc($product['name']) ?></td>
                <td><?= esc($product['category_name']) ?></td>
                <td><?= rupiah($product['price']) ?></td>
                <td>
                    <form class="inline-form" action="<?= site_url('admin/products/' . $product['id'] . '/stock') ?>" method="post">
                        <?= csrf_field() ?>
                        <input class="form-control qty-input" style="padding:6px" type="number" min="0" name="stock" value="<?= (int) $product['stock'] ?>" aria-label="Stok <?= esc($product['name']) ?>">
                        <button class="btn-pearl" type="submit">Simpan</button>
                    </form>
                </td>
                <td><?= esc(ProductStatus::from($product['status'])->label()) ?></td>
                <td class="text-end">
                    <?php if ($isAdmin): ?>
                        <a href="<?= site_url('admin/products/' . $product['id'] . '/edit') ?>">Ubah</a>
                        <form class="inline-form" action="<?= site_url('admin/products/' . $product['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus produk ini? Produk dengan riwayat pesanan akan dinonaktifkan.')">
                            <?= csrf_field() ?>
                            <button class="btn-pearl" type="submit">Hapus</button>
                        </form>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $pager->links('default', 'shop') ?>
<?= $this->endSection() ?>
