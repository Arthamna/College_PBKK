<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<form class="product-form"
	action="<?php echo site_url(isset($product['id']) ? 'admin/products/' . $product['id'] . '/update' : 'admin/products'); ?>"
	method="post" enctype="multipart/form-data">
	<?php echo csrf_field(); ?>
	<div class="panel form-fields">
		<div class="field">
			<label for="name"><?php echo lang('Shop.name'); ?> *</label>
			<input id="name" name="name" type="text" maxlength="150" required
				value="<?php echo input_value('name', $product['name'] ?? ''); ?>"
				<?php if (isset($errors['name'])): ?>aria-invalid="true" aria-describedby="name-error"<?php endif; ?>>
			<?php if (isset($errors['name'])): ?>
				<span class="field-error" id="name-error"><?php echo esc($errors['name']); ?></span>
			<?php endif; ?>
		</div>
		<div class="field">
			<label for="description"><?php echo lang('Shop.description'); ?></label>
			<textarea id="description" name="description" rows="6" maxlength="5000"
				<?php if (isset($errors['description'])): ?>aria-invalid="true" aria-describedby="description-error"<?php endif; ?>><?php echo input_value('description', $product['description'] ?? ''); ?></textarea>
			<?php if (isset($errors['description'])): ?>
				<span class="field-error" id="description-error"><?php echo esc($errors['description']); ?></span>
			<?php endif; ?>
		</div>
		<div class="field-row">
			<div class="field">
				<label for="price"><?php echo lang('Shop.price'); ?> *</label>
				<input id="price" name="price" type="number" min="1" max="2147483647" step="1" required
					value="<?php echo input_value('price', $product['price'] ?? ''); ?>"
					<?php if (isset($errors['price'])): ?>aria-invalid="true" aria-describedby="price-error"<?php endif; ?>>
				<?php if (isset($errors['price'])): ?>
					<span class="field-error" id="price-error"><?php echo esc($errors['price']); ?></span>
				<?php endif; ?>
			</div>
			<div class="field">
				<label for="stock"><?php echo lang('Shop.stock'); ?> *</label>
				<input id="stock" name="stock" type="number" min="0" max="1000000" step="1" required
					value="<?php echo input_value('stock', $product['stock'] ?? 0); ?>"
					<?php if (isset($errors['stock'])): ?>aria-invalid="true" aria-describedby="stock-error"<?php endif; ?>>
				<?php if (isset($errors['stock'])): ?>
					<span class="field-error" id="stock-error"><?php echo esc($errors['stock']); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-actions">
			<button class="button button-dark" type="submit"><?php echo lang('Shop.save'); ?></button>
			<a class="button button-outline" href="<?php echo site_url('admin/products'); ?>"><?php echo lang('Shop.cancel'); ?></a>
		</div>
	</div>
	<aside class="panel upload-panel">
		<label for="image" class="upload-label"><?php echo lang('Shop.image'); ?></label>
		<img class="image-preview" id="image-preview"
			src="<?php echo isset($product['id']) ? product_image($product) : base_url('assets/images/product-placeholder.svg'); ?>"
			alt="Pratinjau gambar produk" width="400" height="400">
		<input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
			aria-describedby="image-help<?php echo isset($errors['image']) ? ' image-error' : ''; ?>"
			<?php if (isset($errors['image'])): ?>aria-invalid="true"<?php endif; ?>>
		<p class="muted small-note" id="image-help">
			<?php echo lang('Shop.image_help'); ?>
			<?php if (isset($product['id'])): ?>
				<?php echo lang('Shop.image_edit_help'); ?>
			<?php endif; ?>
		</p>
		<?php if (isset($errors['image'])): ?>
			<span class="field-error" id="image-error"><?php echo esc($errors['image']); ?></span>
		<?php endif; ?>
	</aside>
</form>
