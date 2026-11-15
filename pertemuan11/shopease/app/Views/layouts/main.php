<?php
use App\Enums\Role;

$user = current_user();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'ShopEase') ?> — ShopEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/shopease.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="global-nav" aria-label="Navigasi utama">
    <div class="inner">
        <a class="brand" href="<?= site_url('/') ?>">ShopEase</a>
        <a href="<?= site_url('products') ?>">Katalog</a>
        <span class="spacer"></span>
        <form class="nav-search" action="<?= site_url('products') ?>" method="get" role="search">
            <input type="search" name="q" placeholder="Cari produk" aria-label="Cari produk" maxlength="100">
        </form>
        <?php if ($user === null): ?>
            <a href="<?= site_url('login') ?>">Masuk</a>
            <a href="<?= site_url('register') ?>">Daftar</a>
        <?php else: ?>
            <?php if ($user['role'] === Role::Customer): ?>
                <a href="<?= site_url('orders') ?>">Pesanan</a>
                <a href="<?= site_url('cart') ?>">Keranjang<?php if (cart_count() > 0): ?><span class="cart-badge"><?= cart_count() ?></span><?php endif ?></a>
            <?php else: ?>
                <a href="<?= site_url('admin') ?>">Dasbor</a>
            <?php endif ?>
            <form action="<?= site_url('logout') ?>" method="post" style="display:inline">
                <?= csrf_field() ?>
                <button class="linklike" type="submit">Keluar (<?= esc($user['name']) ?>)</button>
            </form>
        <?php endif ?>
    </div>
</nav>

<?= view('partials/flash') ?>

<main>
    <?= $this->renderSection('content') ?>
</main>

<footer class="footer">
    <div class="inner">
        <div class="footer-cols">
            <div><h4>Belanja</h4><a href="<?= site_url('products') ?>">Semua produk</a><a href="<?= site_url('products?sort=price_asc') ?>">Harga terendah</a></div>
            <div><h4>Akun</h4><a href="<?= site_url('orders') ?>">Pesanan saya</a><a href="<?= site_url('cart') ?>">Keranjang</a></div>
            <div><h4>Pembayaran</h4><span>Transfer bank manual dengan verifikasi admin.</span></div>
        </div>
        <p class="legal">© <?= date('Y') ?> ShopEase. Toko produk digital — proyek PBKK.</p>
    </div>
</footer>
</body>
</html>
