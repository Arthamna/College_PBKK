<?php
$this->setVar('title', $product === null ? 'Tambah produk' : 'Ubah produk');
$action = $product === null ? site_url('admin/products') : site_url('admin/products/' . $product['id']);
$value  = static fn (string $key, mixed $default = '') => old($key, $product[$key] ?? $default);
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="utility-card" style="max-width:720px">
    <form action="<?= $action ?>" method="post" enctype="multipart/form-data" class="stack">
        <?= csrf_field() ?>
        <div>
            <label class="field-label" for="category_id">Kategori</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= (string) $value('category_id') === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div>
            <label class="field-label" for="name">Nama produk</label>
            <input class="form-control" id="name" name="name" value="<?= esc($value('name')) ?>" required maxlength="150">
        </div>
        <div>
            <label class="field-label" for="description">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" maxlength="5000"><?= esc($value('description')) ?></textarea>
        </div>
        <div class="row g-3">
            <div class="col-6">
                <label class="field-label" for="price">Harga (Rp)</label>
                <input class="form-control" id="price" type="number" min="0" step="1" name="price" value="<?= esc((string) (int) $value('price', 0)) ?>" required>
            </div>
            <div class="col-6">
                <label class="field-label" for="stock">Stok</label>
                <input class="form-control" id="stock" type="number" min="0" step="1" name="stock" value="<?= esc((string) $value('stock', 0)) ?>" required>
            </div>
        </div>
        <div>
            <label class="field-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active" <?= $value('status', 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="inactive" <?= $value('status', 'active') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
        <div>
            <label class="field-label" for="image">Foto produk (JPG/PNG/WEBP, maks. 2 MB)</label>
            <input class="form-control" id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <?php if (! empty($product['image'])): ?><p class="fine-print">Foto saat ini akan diganti jika Anda memilih berkas baru.</p><?php endif ?>
        </div>
        <div class="inline-form"><button class="btn-pill" type="submit">Simpan</button><a href="<?= site_url('admin/products') ?>">Batal</a></div>
    </form>
</div>
<?= $this->endSection() ?>
