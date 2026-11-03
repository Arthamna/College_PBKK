<?php echo $this->extend('layouts/store'); ?>
<?php echo $this->section('content'); ?>
<section class="store-hero" aria-labelledby="hero-title">
	<div class="hero-copy">
		<p class="eyebrow"><span class="status-dot"></span> PILIHAN UNTUK SETIAP HARI</p>
		<h1 id="hero-title">Hal kecil,<br>nyaman <em>setiap hari.</em></h1>
		<p>Temukan barang pilihan untuk menemani aktivitasmu. Sederhana, berguna, dan dekat dengan keseharian.</p>
		<a class="button button-dark" href="#products">Jelajahi koleksi <span aria-hidden="true">↗</span></a>
	</div>
	<div class="hero-art" aria-hidden="true">
		<div class="hero-circle"></div>
		<img src="<?php echo base_url('assets/images/product-placeholder.svg'); ?>" alt="" width="420" height="420">
		<div class="hero-label"><span class="status-dot"></span> Temukan favorit barumu</div>
	</div>
</section>
<section id="products" class="catalog-section" aria-labelledby="catalog-title">
	<div class="section-heading">
		<div><p class="eyebrow">DIPILIH UNTUKMU</p><h2 id="catalog-title">Koleksi produk</h2></div>
		<span class="muted"><?php echo $product_count; ?> produk dalam katalog</span>
	</div>
	<?php if ($products === []): ?>
		<div class="empty-state"><span class="empty-icon" aria-hidden="true">＋</span><h3>Koleksi sedang disiapkan</h3><p>Produk pilihan akan segera hadir. Kunjungi kembali halaman ini nanti.</p></div>
	<?php else: ?>
		<div class="product-grid">
			<?php foreach ($products as $product): ?>
				<article class="product-card">
					<a class="product-visual" href="<?php echo site_url('products/' . $product['id']); ?>" aria-label="Lihat <?php echo esc($product['name'], 'attr'); ?>">
						<img src="<?php echo product_image($product); ?>" alt="<?php echo esc($product['name'], 'attr'); ?>" loading="lazy" width="400" height="400">
						<?php if ((int) $product['stock'] === 0): ?><span class="stock-badge sold-out"><?php echo lang('Shop.out_of_stock'); ?></span><?php endif; ?>
						<span class="visual-arrow" aria-hidden="true">↗</span>
					</a>
					<div class="product-info">
						<h3><a href="<?php echo site_url('products/' . $product['id']); ?>"><?php echo esc($product['name']); ?></a></h3>
						<p class="product-price"><?php echo rupiah((int) $product['price']); ?></p>
						<div class="product-bottom"><span class="muted"><?php echo (int) $product['stock']; ?> tersedia</span>
							<form action="<?php echo site_url('cart/add'); ?>" method="post">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
								<input type="hidden" name="quantity" value="1">
								<button class="button button-outline button-small" type="submit" aria-label="Tambah <?php echo esc($product['name'], 'attr'); ?> ke keranjang" <?php if ((int) $product['stock'] === 0): ?>disabled<?php endif; ?>>+ Keranjang</button>
							</form>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php echo $pager->links('default', 'shop'); ?>
	<?php endif; ?>
</section>
<?php echo $this->endSection(); ?>
