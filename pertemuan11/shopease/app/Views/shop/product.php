<?php
$this->setVar('title', $product['name']);
$stock = (int) $product['stock'];
$user  = current_user();
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <p class="caption"><a href="<?= site_url('products') ?>">Katalog</a> › <a href="<?= site_url('products?category=' . $product['category_id']) ?>"><?= esc($product['category_name']) ?></a></p>
    <div class="product-detail">
        <div class="stage">
            <?php if (! empty($product['image'])): ?>
                <img src="<?= site_url('media/product/' . $product['id']) ?>" alt="<?= esc($product['name']) ?>">
            <?php else: ?>
                <span class="ph" aria-hidden="true"><?= esc(product_initial((string) $product['name'])) ?></span>
            <?php endif ?>
        </div>
        <div>
            <h1 class="display-lg"><?= esc($product['name']) ?></h1>
            <p class="lead-text"><?= rupiah($product['price']) ?></p>
            <p><?= nl2br(esc((string) $product['description'])) ?></p>
            <p class="caption">
                <?php if ($stock === 0): ?><span class="chip chip-bad">Stok habis</span>
                <?php elseif ($stock <= 5): ?><span class="chip chip-wait">Sisa <?= $stock ?></span>
                <?php else: ?><span class="chip chip-ok">Stok tersedia (<?= $stock ?>)</span><?php endif ?>
            </p>
            <?php if ($user === null): ?>
                <a class="btn-pill" href="<?= site_url('login') ?>">Masuk untuk membeli</a>
            <?php elseif ($user['role']->value !== 'customer'): ?>
                <p class="muted">Akun staf tidak dapat berbelanja.</p>
            <?php else: ?>
                <form class="inline-form" action="<?= site_url('cart/add/' . $product['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input class="form-control qty-input" type="number" name="quantity" value="1" min="1" max="<?= max(1, $stock) ?>" aria-label="Kuantitas" <?= $stock === 0 ? 'disabled' : '' ?>>
                    <button class="btn-pill" type="submit" <?= $stock === 0 ? 'disabled' : '' ?>>Tambah ke keranjang</button>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
