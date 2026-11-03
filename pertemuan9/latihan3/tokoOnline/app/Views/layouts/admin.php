<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc($title); ?> · <?php echo lang('Shop.brand'); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="<?php echo base_url('assets/css/app.css'); ?>" rel="stylesheet">
	<script src="<?php echo base_url('assets/js/admin.js'); ?>" defer></script>
</head>
<body class="admin-body">
	<a class="skip-link" href="#main-content">Lewati ke konten</a>
	<header class="site-header">
		<nav class="page-container store-nav" aria-label="Navigasi admin">
			<a class="brand" href="<?php echo site_url('admin/products'); ?>"><span class="brand-mark" aria-hidden="true">r.</span><?php echo lang('Shop.brand'); ?><span class="admin-badge">ADMIN</span></a>
			<div class="nav-links">
				<a href="<?php echo site_url('/'); ?>"><?php echo lang('Shop.back_store'); ?> ↗</a>
				<?php if (session()->has('admin_id')): ?>
					<span class="admin-name"><?php echo esc(session('admin_name')); ?></span>
					<form action="<?php echo site_url('admin/logout'); ?>" method="post">
						<?php echo csrf_field(); ?>
						<button class="button button-outline button-small" type="submit"><?php echo lang('Shop.logout'); ?></button>
					</form>
				<?php endif; ?>
			</div>
		</nav>
	</header>
	<main id="main-content" class="page-container main-content">
		<?php echo $this->include('partials/flash_messages'); ?>
		<?php echo $this->renderSection('content'); ?>
	</main>
	<footer class="site-footer"><div class="page-container footer-content"><span><?php echo lang('Shop.brand'); ?> · <?php echo lang('Shop.admin'); ?></span><span>Proyek latihan · CodeIgniter 4</span></div></footer>
</body>
</html>
