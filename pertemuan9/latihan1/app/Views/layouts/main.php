<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Inventory') ?> | Simple Inventory</title>
    <link rel="stylesheet" href="<?= base_url('assets/sbadmin/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body class="sb-nav-fixed">
<?= $this->include('partials/navbar') ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav"><?= $this->include('partials/sidebar') ?></div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-3 px-lg-4 pb-4">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
        <footer class="py-4 bg-white mt-auto border-top">
            <div class="container-fluid px-4 small text-muted d-flex flex-wrap justify-content-between gap-2">
                <span>Simple Inventory Management System</span>
                <span>CodeIgniter 4 · SB Admin</span>
            </div>
        </footer>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/app.js') ?>"></script>
</body>
</html>
