<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Temukan pilihan barang untuk keseharian di RuangBelanja.">
	<title><?php echo esc($title); ?> · <?php echo lang('Shop.brand'); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="<?php echo base_url('assets/css/app.css'); ?>" rel="stylesheet">
</head>
<body>
	<a class="skip-link" href="#main-content">Lewati ke konten</a>
	<header class="site-header">
		<nav class="page-container store-nav" aria-label="Navigasi utama">
			<a class="brand" href="<?php echo site_url('/'); ?>"><span class="brand-mark" aria-hidden="true">r.</span><?php echo lang('Shop.brand'); ?></a>
			<div class="nav-links">
				<a href="<?php echo site_url('/'); ?>"><?php echo lang('Shop.catalog'); ?></a>
				<a class="cart-link" href="<?php echo site_url('cart'); ?>"><?php echo lang('Shop.cart'); ?><span class="cart-count" aria-label="<?php echo cart_count(); ?> barang"><?php echo cart_count(); ?></span></a>
				<a class="admin-link" href="<?php echo site_url('admin'); ?>"><?php echo lang('Shop.admin'); ?> <span aria-hidden="true">↗</span></a>
			</div>
		</nav>
	</header>
	<main id="main-content" class="page-container main-content">
		<?php echo $this->include('partials/flash_messages'); ?>
		<?php echo $this->renderSection('content'); ?>
	</main>
	<footer class="site-footer">
		<div class="page-container footer-content">
			<span class="brand small-brand"><?php echo lang('Shop.brand'); ?></span>
			<span>Pilihan sederhana untuk keseharian yang lebih berarti.</span>
			<span>Proyek latihan · CodeIgniter 4</span>
		</div>
	</footer>
</body>
</html>
