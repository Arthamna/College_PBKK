<?php $this->setVar('title', 'Kategori'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="page-head"><span></span><a class="btn-pill btn-sm-pill" href="<?= site_url('admin/categories/new') ?>">Tambah kategori</a></div>
<div class="table-wrap">
    <table class="table mb-0">
        <thead><tr><th>Nama</th><th>Slug</th><th>Produk</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= esc($category['name']) ?></td>
                <td><?= esc($category['slug']) ?></td>
                <td><?= (int) $category['product_count'] ?></td>
                <td class="text-end">
                    <a href="<?= site_url('admin/categories/' . $category['id'] . '/edit') ?>">Ubah</a>
                    <form class="inline-form" action="<?= site_url('admin/categories/' . $category['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus kategori ini?')">
                        <?= csrf_field() ?>
                        <button class="btn-pearl" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
