<?php $section = service('uri')->getSegment(1); ?>
<nav class="sb-sidenav accordion sb-sidenav-dark" aria-label="Menu utama">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Menu Utama</div>
            <a class="nav-link <?= $section === 'dashboard' ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>" <?= $section === 'dashboard' ? 'aria-current="page"' : '' ?>>
                <span class="sb-nav-link-icon" aria-hidden="true">▦</span> Dashboard
            </a>
            <a class="nav-link <?= $section === 'products' ? 'active' : '' ?>" href="<?= site_url('products') ?>" <?= $section === 'products' ? 'aria-current="page"' : '' ?>>
                <span class="sb-nav-link-icon" aria-hidden="true">▣</span> Data Barang
            </a>
            <?php if (is_admin()): ?>
                <div class="sb-sidenav-menu-heading">Administrasi</div>
                <a class="nav-link <?= $section === 'categories' ? 'active' : '' ?>" href="<?= site_url('categories') ?>" <?= $section === 'categories' ? 'aria-current="page"' : '' ?>>
                    <span class="sb-nav-link-icon" aria-hidden="true">▤</span> Kategori
                </a>
                <a class="nav-link <?= $section === 'users' ? 'active' : '' ?>" href="<?= site_url('users') ?>" <?= $section === 'users' ? 'aria-current="page"' : '' ?>>
                    <span class="sb-nav-link-icon" aria-hidden="true">♙</span> Pengguna
                </a>
            <?php endif ?>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Login sebagai <?= esc(ucfirst((string) session()->get('role'))) ?></div>
        <strong><?= esc(session()->get('username')) ?></strong>
    </div>
</nav>
