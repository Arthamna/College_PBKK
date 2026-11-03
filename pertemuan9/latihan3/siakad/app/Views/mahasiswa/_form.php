<?php
$errors = session()->getFlashdata('errors') ?? [];
$fields = [
	'nim' => ['type' => 'text', 'max' => 30, 'required' => TRUE],
	'nama' => ['type' => 'text', 'max' => 100, 'required' => TRUE],
	'program_studi' => ['type' => 'text', 'max' => 100, 'required' => TRUE],
	'angkatan' => ['type' => 'number', 'max' => 4, 'required' => TRUE],
	'email' => ['type' => 'email', 'max' => 254, 'required' => FALSE],
];
?>
<?php echo csrf_field(); ?>
<div class="form-grid">
	<?php foreach ($fields as $field => $options): ?>
		<?php $value = old($field, $mahasiswa[$field] ?? '', FALSE); ?>
		<div>
			<label class="form-label" for="<?php echo $field; ?>">
				<?php echo lang('Siakad.' . $field); ?> <?php echo $options['required'] ? '*' : '(' . lang('Siakad.optional') . ')'; ?>
			</label>
			<input class="form-control <?php echo isset($errors[$field]) ? 'is-invalid' : ''; ?>"
				id="<?php echo $field; ?>" name="<?php echo $field; ?>" type="<?php echo $options['type']; ?>"
				value="<?php echo esc(is_scalar($value) ? (string) $value : '', 'attr'); ?>"
				<?php if ($field === 'angkatan'): ?> min="1900" max="2100" step="1"
				<?php else: ?> maxlength="<?php echo $options['max']; ?>"<?php endif; ?>
				<?php echo $options['required'] ? 'required' : ''; ?>
				<?php if (isset($errors[$field])): ?> aria-invalid="true" aria-describedby="<?php echo $field; ?>-error"<?php endif; ?>>
			<?php if (isset($errors[$field])): ?>
				<div class="invalid-feedback" id="<?php echo $field; ?>-error"><?php echo esc($errors[$field]); ?></div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	<div class="full-width">
		<label class="form-label" for="alamat"><?php echo lang('Siakad.alamat'); ?> (<?php echo lang('Siakad.optional'); ?>)</label>
		<?php $alamat = old('alamat', $mahasiswa['alamat'] ?? '', FALSE); ?>
		<textarea class="form-control <?php echo isset($errors['alamat']) ? 'is-invalid' : ''; ?>" id="alamat" name="alamat" rows="4" maxlength="2000"
			<?php if (isset($errors['alamat'])): ?> aria-invalid="true" aria-describedby="alamat-error"<?php endif; ?>><?php echo esc(is_scalar($alamat) ? (string) $alamat : ''); ?></textarea>
		<?php if (isset($errors['alamat'])): ?>
			<div class="invalid-feedback" id="alamat-error"><?php echo esc($errors['alamat']); ?></div>
		<?php endif; ?>
	</div>
</div>
<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('Siakad.save'); ?></button>
	<a class="btn btn-outline-secondary" href="<?php echo site_url('admin/mahasiswa'); ?>"><?php echo lang('Siakad.cancel'); ?></a>
</div>
