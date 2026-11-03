<?php $editing = isset($product['id']); ?>
<?php if ($categories === []): ?>
    <div class="alert alert-warning">Belum ada kategori. <?= is_admin() ? 'Tambahkan kategori terlebih dahulu melalui menu Kategori.' : 'Minta Admin membuat kategori sebelum menambahkan barang.' ?></div>
<?php endif ?>
<div class="card form-card"><div class="card-body">
    <form action="<?= site_url($editing ? 'products/' . $product['id'] . '/update' : 'products') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label><input class="form-control" id="name" name="name" maxlength="150" value="<?= esc(old('name', $product['name'] ?? '', false)) ?>" required></div>
        <div class="mb-3"><label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label><select class="form-select" id="category_id" name="category_id" required><option value="">Pilih kategori</option><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>" <?= (string) old('category_id', $product['category_id'] ?? '', false) === (string) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option><?php endforeach ?></select></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label for="stock" class="form-label">Stok <span class="text-danger">*</span></label><input class="form-control" id="stock" name="stock" type="number" min="0" max="2147483647" step="1" value="<?= esc(old('stock', $product['stock'] ?? 0, false)) ?>" required></div>
            <div class="col-md-6 mb-3"><label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label><input class="form-control" id="price" name="price" type="number" min="0" max="9999999999999.99" step="0.01" value="<?= esc(old('price', $product['price'] ?? '', false)) ?>" required></div>
        </div>
        <div class="mb-4">
            <label for="image" class="form-label">Foto Barang</label>
            <?php if ($product['image'] ?? null): ?><div class="mb-2"><img class="product-preview" src="<?= site_url('products/' . $product['id'] . '/image') ?>" alt="Foto saat ini"></div><?php endif ?>
            <input class="form-control" id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="image-help">
            <div class="form-text" id="image-help">JPG, PNG, atau WebP. Maksimal 2 MB. <?= $editing ? 'Kosongkan untuk mempertahankan foto saat ini.' : 'Opsional.' ?></div>
        </div>
        <div class="d-flex flex-wrap gap-2"><button class="btn btn-primary" type="submit" <?= $categories === [] ? 'disabled' : '' ?>><?= $editing ? 'Simpan Perubahan' : 'Simpan Barang' ?></button><a class="btn btn-outline-secondary" href="<?= site_url('products') ?>">Batal</a></div>
    </form>
</div></div>
