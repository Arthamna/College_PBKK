<header class="topbar">
	<span class="topbar-title"><?php echo esc($title); ?></span>
	<div class="account-menu">
		<span><?php echo esc(session()->get('nama')); ?></span>
		<form action="<?php echo site_url('logout'); ?>" method="post">
			<?php echo csrf_field(); ?>
			<button class="btn btn-outline-secondary btn-sm" type="submit"><?php echo lang('Siakad.logout'); ?></button>
		</form>
	</div>
</header>
