<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc($title); ?> | SIAKAD</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/app.css'); ?>">
</head>
<body>
	<a class="skip-link" href="#main-content"><?php echo lang('Siakad.skip_content'); ?></a>
	<div class="app-shell">
		<?php echo $this->include('partials/sidebar'); ?>
		<div class="app-body">
			<?php echo $this->include('partials/navbar'); ?>
			<main id="main-content" class="content-area" tabindex="-1">
				<?php echo $this->include('partials/flash'); ?>
				<?php echo $this->renderSection('content'); ?>
			</main>
			<footer class="app-footer">SIAKAD &middot; <?php echo lang('Siakad.app_description'); ?></footer>
		</div>
	</div>
	<script src="<?php echo base_url('assets/js/app.js'); ?>" defer></script>
</body>
</html>
