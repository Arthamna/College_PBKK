<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <a class="navbar-brand ps-3" href="<?= site_url('dashboard') ?>">▦ Simple Inventory</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-lg-0" id="sidebarToggle" type="button" aria-label="Buka atau tutup menu" aria-controls="layoutSidenav_nav" aria-expanded="false">☰</button>
    <div class="ms-auto d-flex align-items-center gap-3 pe-3">
        <span class="user-pill d-none d-sm-inline"><?= esc(session()->get('username')) ?> <span class="badge bg-secondary ms-1"><?= esc(ucfirst((string) session()->get('role'))) ?></span></span>
        <form action="<?= site_url('logout') ?>" method="post">
            <?= csrf_field() ?>
            <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
        </form>
    </div>
</nav>
