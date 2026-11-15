<?php
use App\Enums\Role;

$user    = current_user();
$isAdmin = $user !== null && $user['role'] === Role::Administrator;
$path    = service('request')->getUri()->getPath();
$active  = static fn (string $segment): string => str_contains($path, $segment) ? 'active' : '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin') ?> — ShopEase Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/shopease.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="global-nav" aria-label="Navigasi admin">
    <div class="inner">
        <a class="brand" href="<?= site_url('admin') ?>">ShopEase Admin</a>
        <span class="spacer"></span>
        <a href="<?= site_url('/') ?>">Lihat toko</a>
        <form action="<?= site_url('logout') ?>" method="post" style="display:inline">
            <?= csrf_field() ?>
            <button class="linklike" type="submit">Keluar (<?= esc($user['name'] ?? '') ?> · <?= esc($user['role']->label() ?? '') ?>)</button>
        </form>
    </div>
</nav>
<div class="sub-nav">
    <div class="inner">
        <span class="title"><?= esc($title ?? 'Admin') ?></span>
        <a class="<?= $path === 'admin' ? 'active' : '' ?>" href="<?= site_url('admin') ?>">Dasbor</a>
        <a class="<?= $active('admin/orders') ?>" href="<?= site_url('admin/orders') ?>">Pesanan</a>
        <a class="<?= $active('admin/products') ?>" href="<?= site_url('admin/products') ?>">Produk</a>
        <?php if ($isAdmin): ?>
            <a class="<?= $active('admin/categories') ?>" href="<?= site_url('admin/categories') ?>">Kategori</a>
            <a class="<?= $active('admin/customers') ?>" href="<?= site_url('admin/customers') ?>">Pelanggan</a>
            <a class="<?= $active('admin/reports') ?>" href="<?= site_url('admin/reports') ?>">Laporan</a>
        <?php endif ?>
    </div>
</div>

<?= view('partials/flash') ?>

<main class="page">
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
