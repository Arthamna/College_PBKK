<?php
$this->setVar('title', $category === null ? 'Tambah kategori' : 'Ubah kategori');
$action = $category === null ? site_url('admin/categories') : site_url('admin/categories/' . $category['id']);
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="utility-card" style="max-width:640px">
    <form action="<?= $action ?>" method="post" class="stack">
        <?= csrf_field() ?>
        <div>
            <label class="field-label" for="name">Nama</label>
            <input class="form-control" id="name" name="name" value="<?= esc(old('name', $category['name'] ?? '')) ?>" required maxlength="100">
        </div>
        <div>
            <label class="field-label" for="description">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" maxlength="500"><?= esc(old('description', $category['description'] ?? '')) ?></textarea>
        </div>
        <div class="inline-form"><button class="btn-pill" type="submit">Simpan</button><a href="<?= site_url('admin/categories') ?>">Batal</a></div>
    </form>
</div>
<?= $this->endSection() ?>
