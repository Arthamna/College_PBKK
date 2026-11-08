<?= $this->extend('shop/layout') ?>
<?= $this->section('pageClass') ?>catalog-page<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php if ($search===''): ?>
<section class="market-hero" aria-labelledby="hero-title">
    <div class="hero-copy">
        <span class="hero-eyebrow">SEDIKIT KLIK, BANYAK PILIHAN</span>
        <h1 id="hero-title">Semua kebutuhan.<br>Satu tujuan.</h1>
        <p>Dari hal kecil sampai favorit baru.<br>Temukan semuanya di ShopEase.</p>
        <a class="hero-cta" href="#katalog">Mulai belanja <span aria-hidden="true">→</span></a>
        <span class="hero-caption">Belanja mudah, setiap hari.</span>
    </div>
    <img class="hero-art" src="<?= base_url('assets/shopping-scene.svg') ?>" alt="Ilustrasi pilihan belanja: tas, headphone, buku, dan perlengkapan rumah" fetchpriority="high">
</section>
<section id="pilihan" class="discovery-grid" aria-label="Pilihan belanja">
    <article class="discovery-card">
        <h2>Teman aktivitas harian</h2>
        <div class="discovery-quad">
            <a href="<?= site_url('products').'?q=Buku' ?>"><div class="tile-image tile-books"><span aria-hidden="true">▤</span></div><span>Buku &amp; bacaan</span></a>
            <a href="<?= site_url('products').'?q=Tas' ?>"><div class="tile-image tile-bag"><span aria-hidden="true">▱</span></div><span>Tas sehari-hari</span></a>
            <a href="<?= site_url('products').'?q=Pulpen' ?>"><div class="tile-image tile-pens"><span aria-hidden="true">╱</span></div><span>Alat tulis</span></a>
            <a href="#katalog"><div class="tile-image tile-more"><span aria-hidden="true">＋</span></div><span>Favorit lainnya</span></a>
        </div>
        <a class="discovery-link" href="#katalog">Jelajahi pilihan produk <span aria-hidden="true">→</span></a>
    </article>
    <article class="discovery-card">
        <h2>Hal kecil, manfaat besar</h2>
        <a class="feature-tile feature-stationery" href="<?= site_url('products').'?q=Buku' ?>" aria-label="Jelajahi buku"><span class="tile-kicker">RUANG UNTUK IDE BARU</span><div class="notebook-art" aria-hidden="true"><i></i><b>good<br>things<br>ahead.</b></div><span class="feature-caption">Mulai cerita barumu.</span></a>
        <a class="discovery-link" href="<?= site_url('products').'?q=Buku' ?>">Temukan buku pilihan <span aria-hidden="true">→</span></a>
    </article>
    <article class="discovery-card">
        <h2>Siap menemani harimu</h2>
        <a class="feature-tile feature-bag" href="<?= site_url('products').'?q=Tas' ?>" aria-label="Jelajahi tas"><span class="tile-kicker">BAWA SEMUA RENCANAMU</span><div class="bag-art" aria-hidden="true"><i></i><b></b></div><span class="feature-caption">Ke mana pun tujuanmu.</span></a>
        <a class="discovery-link" href="<?= site_url('products').'?q=Tas' ?>">Cari tas favoritmu <span aria-hidden="true">→</span></a>
    </article>
    <article class="discovery-card">
        <h2>Lebih mudah di ShopEase</h2>
        <a class="feature-tile feature-welcome" href="<?= site_url($user ? 'products' : 'register') ?>"><span class="tile-kicker">BELANJA TANPA RIBET</span><span class="welcome-mark" aria-hidden="true">s<span>e.</span></span><strong>Kebutuhanmu,<br>tinggal selangkah.</strong><span class="welcome-arrow" aria-hidden="true">↗</span></a>
        <a class="discovery-link" href="<?= site_url($user ? 'products' : 'register') ?>"><?= $user ? 'Lihat semua produk' : 'Buat akun, mulai belanja' ?> <span aria-hidden="true">→</span></a>
    </article>
</section>
<?php endif ?>
<section id="katalog" class="catalog-section" aria-labelledby="catalog-title">
    <div class="section-heading"><div><span class="section-eyebrow"><?= $search!=='' ? 'TEMUKAN YANG KAMU CARI' : 'PILIHAN UNTUKMU' ?></span><h2 id="catalog-title"><?= $search!=='' ? 'Hasil pencarian “'.esc($search).'”' : 'Jelajahi produk kami' ?></h2></div><span class="product-count"><?= count($products) ?> produk<?= $search!=='' ? ' ditemukan' : ' tersedia' ?></span></div>
    <?php if ($products): ?>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
        <article class="card catalog-product">
            <a class="product-visual" href="<?= site_url('products/'.$p['id']) ?>" aria-label="<?= esc($p['name']) ?>">
                <?php if ($p['image']): ?><img class="product-image" src="<?= site_url('products/'.$p['id'].'/image') ?>" alt="<?= esc($p['name']) ?>" loading="lazy"><?php else: ?><div class="product-placeholder" aria-hidden="true"><svg viewBox="0 0 100 100"><path d="m50 16 32 18v33L50 85 18 67V34Z M18 34l32 19 32-19 M50 53v32 M34 25l32 19v15"/></svg><span>ShopEase</span></div><?php endif ?>
                <span class="product-status <?= $p['stock']>0 ? '' : 'out-of-stock' ?>"><?= $p['stock']>0 ? 'Tersedia' : 'Stok habis' ?></span>
            </a>
            <div class="card-body"><h3><a href="<?= site_url('products/'.$p['id']) ?>"><?= esc($p['name']) ?></a></h3><p class="product-description"><?= esc($p['description']) ?></p><p class="product-price"><span>Rp</span> <?= esc(number_format((float)$p['price'],0,',','.')) ?></p><div class="product-bottom"><span>Stok: <?= esc($p['stock']) ?></span><a class="btn btn-primary" href="<?= site_url('products/'.$p['id']) ?>">Lihat produk <span aria-hidden="true">→</span></a></div></div>
        </article>
        <?php endforeach ?>
    </div>
    <?php else: ?>
        <div class="catalog-empty"><span aria-hidden="true">⌕</span><h3><?= $search!=='' ? 'Produk belum ditemukan' : 'Koleksi baru segera hadir' ?></h3><p><?= $search!=='' ? 'Coba kata kunci lain atau jelajahi semua produk kami.' : 'Belum ada produk aktif. Kunjungi lagi untuk melihat pilihan terbaru.' ?></p><?php if ($search!==''): ?><a class="btn btn-primary" href="<?= site_url('products') ?>">Lihat semua produk</a><?php endif ?></div>
    <?php endif ?>
</section>
<section class="shopping-note"><span class="note-icon" aria-hidden="true">✓</span><div><strong>Belanja jadi lebih sederhana.</strong><p>Pilih produk, tambahkan ke keranjang, dan pantau pesanan dari akunmu.</p></div><a href="<?= site_url($user ? ($user['role']==='customer' ? 'orders' : 'admin/orders') : 'login') ?>"><?= $user ? 'Lihat pesanan' : 'Masuk ke akun' ?> <span aria-hidden="true">→</span></a></section>
<?= $this->endSection() ?>
