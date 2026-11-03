<?php if ($message = session()->getFlashdata('success')): ?>
	<div class="alert alert-success" role="status"><?php echo esc($message); ?></div>
<?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?>
	<div class="alert alert-danger" role="alert"><?php echo esc($message); ?></div>
<?php endif; ?>
<?php if ($errors = session()->getFlashdata('errors')): ?>
	<div class="alert alert-danger" role="alert">
		<strong><?php echo lang('Siakad.validation_intro'); ?></strong>
		<ul class="mb-0 mt-2">
			<?php foreach ($errors as $message): ?>
				<li><?php echo esc($message); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
