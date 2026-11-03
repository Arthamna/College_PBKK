<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<section class="login-panel">
	<div class="login-intro"><span class="brand-mark large-mark" aria-hidden="true">r.</span><p class="eyebrow">RUANG UNTUK MENGELOLA</p><h1><?php echo lang('Shop.login'); ?></h1><p><?php echo lang('Shop.login_intro'); ?></p><a class="text-link" href="<?php echo site_url('/'); ?>">← <?php echo lang('Shop.back_store'); ?></a></div>
	<div class="login-form-panel">
		<form action="<?php echo site_url('admin/login'); ?>" method="post">
			<?php echo csrf_field(); ?>
			<div class="field"><label for="email"><?php echo lang('Shop.email'); ?></label><input id="email" name="email" type="email" maxlength="150" autocomplete="username" value="<?php echo esc(session()->getFlashdata('login_email') ?? '', 'attr'); ?>" placeholder="admin@example.com" required></div>
			<div class="field"><label for="password"><?php echo lang('Shop.password'); ?></label><input id="password" name="password" type="password" maxlength="1024" autocomplete="current-password" required></div>
			<button class="button button-dark full-width" type="submit"><?php echo lang('Shop.login'); ?> <span aria-hidden="true">→</span></button>
		</form>
		<p class="muted small-note"><?php echo lang('Shop.login_note'); ?></p>
	</div>
</section>
<?php echo $this->endSection(); ?>
