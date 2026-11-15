<?php $this->setVar('title', 'Produk digital'); ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="tile tile-parchment">
    <div class="narrow">
        <h1 class="hero-display">Produk digital. Langsung beres.</h1>
        <p class="lead-text">E-book, template, software, dan kursus pilihan dalam satu toko.</p>
        <div class="tile-actions">
            <a class="btn-pill" href="<?= site_url('products') ?>">Belanja sekarang</a>
            <a class="btn-pill-ghost" href="<?= site_url('products?sort=price_asc') ?>">Harga terendah</a>
        </div>
    </div>
</section>

<section class="tile tile-dark on-dark">
    <div class="wrap">
        <h2 class="display-lg">Jelajahi kategori</h2>
        <p class="lead-text muted">Temukan yang Anda butuhkan.</p>
        <div class="card-grid" style="margin-top:40px">
            <?php foreach ($categories as $category): ?>
                <a class="utility-card cat-card" href="<?= site_url('products?category=' . $category['id']) ?>">
                    <h3 class="tagline"><?= esc($category['name']) ?></h3>
                    <p class="caption"><?= esc((string) $category['description']) ?></p>
                    <span class="count"><?= (int) $category['product_count'] ?> produk</span>
                </a>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="tile tile-light">
    <div class="wrap">
        <h2 class="display-lg">Produk terbaru</h2>
        <p class="lead-text muted">Baru saja tiba.</p>
        <div class="card-grid" style="margin-top:40px">
            <?php foreach ($latest as $product): ?>
                <?= view('shop/_product_card', ['product' => $product]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="tile tile-dark-2 on-dark">
    <div class="wrap">
        <h2 class="display-lg">Produk terlaris</h2>
        <p class="lead-text muted">Paling banyak dipilih pelanggan.</p>
        <?php if ($bestSellers === []): ?>
            <p class="muted" style="margin-top:32px">Belum ada penjualan. Jadilah yang pertama.</p>
        <?php else: ?>
            <div class="card-grid" style="margin-top:40px">
                <?php foreach ($bestSellers as $product): ?>
                    <?= view('shop/_product_card', ['product' => $product]) ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?= $this->endSection() ?>
