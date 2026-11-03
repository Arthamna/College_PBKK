<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Simple Inventory</title>
    <link rel="stylesheet" href="<?= base_url('assets/sbadmin/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="login-page">
<main class="login-card">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="login-mark mb-3" aria-hidden="true">▦</div>
                <h1>Simple Inventory</h1>
                <p class="text-muted mb-0">Masuk untuk mengelola persediaan barang.</p>
            </div>
            <?= $this->include('partials/alerts') ?>
            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input class="form-control" id="username" name="username" type="text" maxlength="50" autocomplete="username" required autofocus>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input class="form-control" id="password" name="password" type="password" maxlength="255" autocomplete="current-password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Masuk</button>
            </form>
        </div>
    </div>
    <p class="text-center text-white-50 small mt-4 mb-0">Simple Inventory Management System</p>
</main>
</body>
</html>
