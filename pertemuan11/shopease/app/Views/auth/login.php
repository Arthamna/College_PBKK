<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page narrow">
    <div class="utility-card auth-card">
        <h1 class="display-md">Masuk</h1>
        <form action="<?= site_url('login') ?>" method="post" class="stack" novalidate>
            <?= csrf_field() ?>
            <div>
                <label class="field-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required autocomplete="username">
            </div>
            <div>
                <label class="field-label" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button class="btn-pill" type="submit">Masuk</button>
            <p class="caption">Belum punya akun? <a href="<?= site_url('register') ?>">Daftar sekarang</a></p>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
