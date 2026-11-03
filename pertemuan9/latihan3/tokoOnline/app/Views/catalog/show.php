<?php echo $this->extend('layouts/store'); ?>
<?php echo $this->section('content'); ?>
<nav class="breadcrumb-nav" aria-label="Breadcrumb"><a href="<?php echo site_url('/'); ?>"><?php echo lang('Shop.catalog'); ?></a><span aria-hidden="true">/</span><span><?php echo esc($product['name']); ?></span></nav>
<article class="product-detail">
	<div class="detail-visual"><img src="<?php echo product_image($product); ?>" alt="<?php echo esc($product['name'], 'attr'); ?>" width="600" height="600"></div>
	<div class="detail-copy">
		<p class="eyebrow">PILIHAN RUANGBELANJA</p>
		<h1><?php echo esc($product['name']); ?></h1>
		<p class="detail-price"><?php echo rupiah((int) $product['price']); ?></p>
		<span class="stock-badge <?php echo (int) $product['stock'] === 0 ? 'sold-out' : ''; ?>"><?php echo (int) $product['stock'] === 0 ? lang('Shop.out_of_stock') : lang('Shop.available', [(int) $product['stock']]); ?></span>
		<div class="detail-description"><h2>Tentang produk</h2><p><?php echo nl2br(esc($product['description'] ?: 'Deskripsi produk belum tersedia.')); ?></p></div>
		<?php if ((int) $product['stock'] > 0): ?>
			<form class="add-cart-form" action="<?php echo site_url('cart/add'); ?>" method="post">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
				<div class="field"><label for="quantity"><?php echo lang('Shop.quantity'); ?></label><input id="quantity" name="quantity" type="number" min="1" max="<?php echo (int) $product['stock']; ?>" value="1" required></div>
				<button class="button button-dark" type="submit"><?php echo lang('Shop.add_cart'); ?> <span aria-hidden="true">+</span></button>
			</form>
		<?php else: ?>
			<p class="notice notice-info">Produk ini sedang habis. Silakan lihat pilihan lainnya.</p>
		<?php endif; ?>
		<p class="muted small-note">Jumlah di keranjang mengikuti ketersediaan stok terbaru.</p>
	</div>
</article>
<?php echo $this->endSection(); ?>
