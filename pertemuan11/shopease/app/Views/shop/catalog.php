<?php
use App\Models\ProductModel;

$this->setVar('title', 'Katalog');
$labels = ['newest' => 'Terbaru', 'price_asc' => 'Harga terendah', 'price_desc' => 'Harga tertinggi', 'name' => 'Nama A–Z'];
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <div class="page-head"><h1 class="display-md" style="margin:0">Katalog</h1></div>

    <form method="get" action="<?= site_url('products') ?>" class="row g-3 align-items-end" style="margin-bottom:24px" role="search">
        <div class="col-12 col-md-5">
            <label class="field-label" for="q">Cari</label>
            <input class="form-control search-input" id="q" type="search" name="q" value="<?= esc($q) ?>" maxlength="100" placeholder="Nama atau deskripsi produk">
        </div>
        <div class="col-6 col-md-3">
            <label class="field-label" for="category">Kategori</label>
            <select class="form-select" id="category" name="category">
                <option value="">Semua</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="field-label" for="sort">Urutkan</label>
            <select class="form-select" id="sort" name="sort">
                <?php foreach (array_keys(ProductModel::SORTS) as $key): ?>
                    <option value="<?= esc($key) ?>" <?= $sort === $key ? 'selected' : '' ?>><?= esc($labels[$key]) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-12 col-md-2"><button class="btn-pill w-100" type="submit">Terapkan</button></div>
    </form>

    <?php if ($products === []): ?>
        <div class="utility-card"><p style="margin:0">Tidak ada produk yang cocok. <a href="<?= site_url('products') ?>">Reset filter</a></p></div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($products as $product): ?>
                <?= view('shop/_product_card', ['product' => $product]) ?>
            <?php endforeach ?>
        </div>
        <?= $pager->links('default', 'shop') ?>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
