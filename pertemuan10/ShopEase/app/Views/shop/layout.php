<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#232f3e">
    <title>ShopEase — Belanja jadi lebih mudah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/shop.css') ?>" rel="stylesheet">
</head>
<body id="top">
<a class="skip-link" href="#main-content">Lewati ke konten</a>
<header class="shop-header">
    <div class="header-main">
        <a class="brand" href="<?= site_url('/') ?>" aria-label="ShopEase beranda">shop<span>ease</span><svg viewBox="0 0 100 18" aria-hidden="true"><path d="M5 3 Q48 25 91 3 M80 3 L93 2 L89 14"/></svg></a>
        <div class="delivery"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span><small>Belanja dari</small><strong>Indonesia</strong></span></div>
        <form class="header-search" action="<?= site_url('products') ?>" method="get" role="search">
            <span class="search-scope">Semua</span>
            <label class="visually-hidden" for="product-search">Cari produk di ShopEase</label>
            <input id="product-search" type="search" name="q" placeholder="Cari kebutuhanmu di ShopEase" value="<?= esc($search ?? '') ?>">
            <button type="submit" aria-label="Cari produk"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg></button>
        </form>
        <span class="header-language">ID <span aria-hidden="true">⌄</span></span>
        <?php if ($user): ?>
            <div class="header-account"><small>Halo, <?= esc($user['name']) ?></small><form action="<?= site_url('logout') ?>" method="post"><?= csrf_field() ?><button class="header-logout">Keluar akun <span aria-hidden="true">↗</span></button></form></div>
        <?php else: ?>
            <a class="header-account" href="<?= site_url('login') ?>"><small>Halo, selamat datang</small><strong>Masuk / Daftar <span aria-hidden="true">⌄</span></strong></a>
        <?php endif ?>
        <a class="header-orders" href="<?= site_url($user && $user['role']!=='customer' ? 'admin/orders' : 'orders') ?>"><small>Riwayat &amp;</small><strong>Pesanan</strong></a>
        <?php if (!$user || $user['role']==='customer'): ?>
            <a class="header-cart" href="<?= site_url('cart') ?>"><svg viewBox="0 0 32 32" aria-hidden="true"><path d="M2 5h5l4 17h15l4-12H9"/><circle cx="13" cy="27" r="1.5"/><circle cx="25" cy="27" r="1.5"/></svg><strong>Keranjang</strong></a>
        <?php endif ?>
    </div>
    <nav class="header-nav" aria-label="Navigasi utama">
        <div class="nav-links">
            <a class="nav-all" href="<?= site_url('products') ?>"><span aria-hidden="true">☰</span> Semua produk</a>
            <a href="<?= site_url('products') ?>#pilihan">Pilihan belanja</a>
            <a href="<?= site_url('products') ?>#katalog">Jelajahi katalog</a>
            <?php if ($user && $user['role']==='administrator'): ?>
                <a href="<?= site_url('admin/categories') ?>">Kelola kategori</a><a href="<?= site_url('admin/products') ?>">Kelola produk</a>
            <?php elseif (!$user): ?>
                <a href="<?= site_url('register') ?>">Daftar sekarang</a>
            <?php endif ?>
        </div>
        <span class="nav-tagline">Kebutuhan sehari-hari, dalam satu tempat</span>
    </nav>
</header>
<main id="main-content" class="shop-main <?= $this->renderSection('pageClass') ?>">
    <?php foreach (['error'=>'danger','message'=>'success'] as $key=>$color): ?>
        <?php if (session()->getFlashdata($key)): ?><div role="alert" class="alert alert-<?= $color ?>"><?= esc(session()->getFlashdata($key)) ?></div><?php endif ?>
    <?php endforeach ?>
    <?= $this->renderSection('content') ?>
</main>
<footer class="shop-footer">
    <a class="back-top" href="#top">Kembali ke atas <span aria-hidden="true">↑</span></a>
    <div class="footer-content"><a class="brand" href="<?= site_url('/') ?>">shop<span>ease</span></a><p>Temukan kebutuhanmu. Nikmati mudahnya belanja.</p><span>Indonesia · Bahasa Indonesia · IDR</span></div>
    <div class="footer-bottom">&copy; <?= date('Y') ?> ShopEase · Toko online pembelajaran</div>
</footer>
</body>
</html>
