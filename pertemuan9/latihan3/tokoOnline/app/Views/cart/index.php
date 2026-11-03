<?php echo $this->extend('layouts/store'); ?>
<?php echo $this->section('content'); ?>
<div class="page-heading"><p class="eyebrow">PILIHANMU</p><h1>Keranjang belanja</h1><p class="muted">Periksa kembali barang favoritmu di sini.</p></div>
<?php if ($cart['adjusted']): ?><div class="notice notice-info" role="status"><?php echo lang('Shop.cart_adjusted'); ?></div><?php endif; ?>
<?php if ($cart['items'] === []): ?>
	<div class="empty-state"><span class="empty-icon" aria-hidden="true">＋</span><h2>Keranjangmu masih kosong</h2><p>Yuk, temukan barang pilihan yang cocok untuk keseharianmu.</p><a class="button button-dark" href="<?php echo site_url('/'); ?>">Jelajahi koleksi ↗</a></div>
<?php else: ?>
	<div class="cart-layout">
		<section aria-label="Produk dalam keranjang">
			<div class="cart-items">
				<?php foreach ($cart['items'] as $item): ?>
					<?php $product = $item['product']; ?>
					<article class="cart-item">
						<a class="cart-image" href="<?php echo site_url('products/' . $product['id']); ?>"><img src="<?php echo product_image($product); ?>" alt="<?php echo esc($product['name'], 'attr'); ?>" width="120" height="120"></a>
						<div class="cart-product"><h2><a href="<?php echo site_url('products/' . $product['id']); ?>"><?php echo esc($product['name']); ?></a></h2><p class="muted"><?php echo rupiah((int) $product['price']); ?> / buah</p>
							<form class="quantity-form" action="<?php echo site_url('cart/update/' . $product['id']); ?>" method="post">
								<?php echo csrf_field(); ?>
								<label class="visually-hidden" for="quantity-<?php echo $product['id']; ?>">Jumlah <?php echo esc($product['name']); ?></label>
								<input id="quantity-<?php echo $product['id']; ?>" name="quantity" type="number" min="1" max="<?php echo (int) $product['stock']; ?>" value="<?php echo $item['quantity']; ?>" required>
								<button class="button button-outline button-small" type="submit">Perbarui</button>
							</form>
						</div>
						<div class="cart-item-total"><strong><?php echo rupiah($item['subtotal']); ?></strong><form action="<?php echo site_url('cart/remove/' . $product['id']); ?>" method="post"><?php echo csrf_field(); ?><button class="text-button danger" type="submit" aria-label="Hapus <?php echo esc($product['name'], 'attr'); ?> dari keranjang"><?php echo lang('Shop.delete'); ?></button></form></div>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="cart-actions"><a class="text-link" href="<?php echo site_url('/'); ?>">← Lanjut belanja</a><form action="<?php echo site_url('cart/clear'); ?>" method="post"><?php echo csrf_field(); ?><button class="text-button" type="submit">Kosongkan keranjang</button></form></div>
		</section>
		<aside class="summary-panel" aria-labelledby="summary-title"><p class="eyebrow">PILIHAN YANG BAGUS</p><h2 id="summary-title">Ringkasan belanja</h2><div class="summary-line"><span>Total barang</span><strong><?php echo $cart['count']; ?> buah</strong></div><div class="summary-line summary-total"><span>Total harga</span><strong><?php echo rupiah($cart['total']); ?></strong></div><p class="muted small-note">Harga mengikuti katalog terbaru. Barang di keranjang belum mengurangi stok.</p><div class="notice notice-info">Aplikasi latihan ini mendukung katalog dan keranjang. Checkout belum tersedia.</div></aside>
	</div>
<?php endif; ?>
<?php echo $this->endSection(); ?>
