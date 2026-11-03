<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Kategori</h1><p>Atur kelompok barang untuk memudahkan pengelolaan.</p></div><a class="btn btn-primary" href="<?= site_url('categories/create') ?>">+ Tambah Kategori</a></div>
<?= $this->include('partials/alerts') ?>
<div class="card"><div class="table-responsive">
    <table class="table table-hover"><caption class="visually-hidden">Daftar kategori barang</caption>
        <thead><tr><th class="ps-4">No.</th><th>Nama Kategori</th><th class="pe-4">Aksi</th></tr></thead>
        <tbody><?php foreach ($categories as $index => $category): ?><tr>
            <td class="ps-4"><?= $index + 1 ?></td><td class="fw-semibold"><?= esc($category['name']) ?></td>
            <td class="pe-4"><div class="action-buttons"><a class="btn btn-sm btn-outline-primary" href="<?= site_url('categories/' . $category['id'] . '/edit') ?>">Edit</a><form method="post" action="<?= site_url('categories/' . $category['id'] . '/delete') ?>" data-confirm="Hapus kategori ini?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button></form></div></td>
        </tr><?php endforeach ?>
        <?php if ($categories === []): ?><tr><td colspan="3" class="empty-state">Belum ada kategori. Tambahkan kategori pertamamu.</td></tr><?php endif ?></tbody>
    </table>
</div></div>
<?= $this->endSection() ?>
