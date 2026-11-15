<?php /** @var array<string, mixed> $product */ ?>
<article class="store-card">
    <a class="thumb" href="<?= site_url('products/' . $product['slug']) ?>" aria-label="<?= esc($product['name']) ?>">
        <?php if (! empty($product['image'])): ?>
            <img src="<?= site_url('media/product/' . $product['id']) ?>" alt="<?= esc($product['name']) ?>" loading="lazy">
        <?php else: ?>
            <span class="ph" aria-hidden="true"><?= esc(product_initial((string) $product['name'])) ?></span>
        <?php endif ?>
    </a>
    <?php if (! empty($product['category_name'])): ?><span class="cat"><?= esc($product['category_name']) ?></span><?php endif ?>
    <a class="name" href="<?= site_url('products/' . $product['slug']) ?>" style="color:inherit"><?= esc($product['name']) ?></a>
    <div class="price"><?= rupiah($product['price']) ?>
        <?php if ((int) $product['stock'] === 0): ?><span class="chip chip-bad">Habis</span><?php endif ?>
    </div>
    <div class="actions">
        <a href="<?= site_url('products/' . $product['slug']) ?>">Lihat detail</a>
    </div>
</article>
