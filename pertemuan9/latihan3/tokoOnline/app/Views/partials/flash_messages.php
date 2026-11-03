<?php foreach (['success', 'error'] as $message_type): ?>
	<?php if (session()->getFlashdata($message_type)): ?>
		<div class="notice notice-<?php echo $message_type; ?>" role="<?php echo $message_type === 'error' ? 'alert' : 'status'; ?>"><?php echo esc(session()->getFlashdata($message_type)); ?></div>
	<?php endif; ?>
<?php endforeach; ?>
<?php $errors = session()->getFlashdata('errors') ?? []; ?>
<?php if ($errors !== []): ?>
	<div class="notice notice-error" role="alert">
		<strong><?php echo lang('Shop.fix_errors'); ?></strong>
		<ul><?php foreach ($errors as $error): ?><li><?php echo esc($error); ?></li><?php endforeach; ?></ul>
	</div>
<?php endif; ?>
