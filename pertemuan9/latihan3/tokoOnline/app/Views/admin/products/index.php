<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<div class="section-heading admin-heading">
	<div>
		<p class="eyebrow"><?php echo lang('Shop.admin'); ?></p>
		<h1><?php echo lang('Shop.products'); ?></h1>
		<p class="muted"><?php echo lang('Shop.admin_intro'); ?></p>
	</div>
	<a class="button button-dark" href="<?php echo site_url('admin/products/create'); ?>">+ <?php echo lang('Shop.add_product'); ?></a>
</div>
<section class="panel" aria-labelledby="product-list-title">
	<div class="panel-heading">
		<h2 id="product-list-title"><?php echo lang('Shop.catalog'); ?></h2>
		<span class="count-badge"><?php echo $product_count; ?> produk</span>
	</div>
	<?php if ($products === []): ?>
		<div class="empty-state">
			<h3><?php echo lang('Shop.empty_products'); ?></h3>
			<p><?php echo lang('Shop.empty_products_help'); ?></p>
			<a class="button button-dark" href="<?php echo site_url('admin/products/create'); ?>"><?php echo lang('Shop.add_product'); ?></a>
		</div>
	<?php else: ?>
		<div class="table-scroll" tabindex="0" role="region" aria-label="Daftar produk, geser untuk melihat seluruh kolom">
			<table class="product-table">
				<thead>
					<tr>
						<th scope="col"><?php echo lang('Shop.name'); ?></th>
						<th scope="col"><?php echo lang('Shop.price'); ?></th>
						<th scope="col"><?php echo lang('Shop.stock'); ?></th>
						<th scope="col"><?php echo lang('Shop.actions'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $product): ?>
						<tr>
							<td>
								<div class="table-product">
									<img src="<?php echo product_image($product); ?>" alt="" width="56" height="56" loading="lazy">
									<div>
										<a href="<?php echo site_url('products/' . $product['id']); ?>"><?php echo esc($product['name']); ?></a>
										<span class="muted">#<?php echo $product['id']; ?></span>
									</div>
								</div>
							</td>
							<td class="nowrap"><?php echo rupiah((int) $product['price']); ?></td>
							<td>
								<span class="stock-badge <?php echo (int) $product['stock'] === 0 ? 'sold-out' : ''; ?>"><?php echo (int) $product['stock']; ?></span>
							</td>
							<td>
								<div class="table-actions">
									<a class="button button-outline button-small" href="<?php echo site_url('admin/products/' . $product['id'] . '/edit'); ?>"><?php echo lang('Shop.edit'); ?></a>
									<form action="<?php echo site_url('admin/products/' . $product['id'] . '/delete'); ?>"
										method="post" data-confirm="<?php echo esc(lang('Shop.delete_confirm'), 'attr'); ?>">
										<?php echo csrf_field(); ?>
										<button class="button button-danger button-small" type="submit" aria-label="Hapus <?php echo esc($product['name'], 'attr'); ?>"><?php echo lang('Shop.delete'); ?></button>
									</form>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php echo $pager->links('default', 'shop'); ?>
	<?php endif; ?>
</section>
<?php echo $this->endSection(); ?>
