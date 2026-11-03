<?php $editing = isset($category['id']); ?>
<div class="card form-card"><div class="card-body">
    <form action="<?= site_url($editing ? 'categories/' . $category['id'] . '/update' : 'categories') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-4"><label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label><input class="form-control" id="name" name="name" maxlength="100" value="<?= esc(old('name', $category['name'] ?? '', false)) ?>" required autofocus></div>
        <div class="d-flex flex-wrap gap-2"><button class="btn btn-primary" type="submit"><?= $editing ? 'Simpan Perubahan' : 'Simpan Kategori' ?></button><a class="btn btn-outline-secondary" href="<?= site_url('categories') ?>">Batal</a></div>
    </form>
</div></div>
