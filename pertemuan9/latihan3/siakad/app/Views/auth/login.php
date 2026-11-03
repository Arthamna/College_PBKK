<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc($title); ?> | SIAKAD</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/app.css'); ?>">
</head>
<body class="login-page">
	<main class="login-card">
		<div class="brand-icon mb-4" aria-hidden="true">S</div>
		<p class="eyebrow"><?php echo lang('Siakad.app_description'); ?></p>
		<h1 class="mb-3">SIAKAD</h1>
		<p class="text-secondary mb-4"><?php echo lang('Siakad.login_intro'); ?></p>
		<?php echo $this->include('partials/flash'); ?>
		<form action="<?php echo site_url('login'); ?>" method="post">
			<?php echo csrf_field(); ?>
			<div class="mb-3">
				<label class="form-label" for="username"><?php echo lang('Siakad.username'); ?></label>
				<input class="form-control" id="username" name="username" type="text" maxlength="50" autocomplete="username" required autofocus>
			</div>
			<div class="mb-4">
				<label class="form-label" for="password"><?php echo lang('Siakad.password'); ?></label>
				<input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
			</div>
			<button class="btn btn-primary w-100" type="submit"><?php echo lang('Siakad.login'); ?></button>
		</form>
	</main>
</body>
</html>
