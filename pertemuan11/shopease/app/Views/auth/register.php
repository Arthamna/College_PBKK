<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page narrow">
    <div class="utility-card auth-card">
        <h1 class="display-md">Buat akun</h1>
        <form action="<?= site_url('register') ?>" method="post" class="stack" novalidate>
            <?= csrf_field() ?>
            <div>
                <label class="field-label" for="name">Nama lengkap</label>
                <input class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>" required maxlength="100" autocomplete="name">
            </div>
            <div>
                <label class="field-label" for="email">Email</label>
                <input class="form-control" type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required maxlength="150" autocomplete="email">
            </div>
            <div>
                <label class="field-label" for="phone">Telepon (opsional)</label>
                <input class="form-control" id="phone" name="phone" value="<?= esc(old('phone')) ?>" maxlength="30" autocomplete="tel">
            </div>
            <div>
                <label class="field-label" for="password">Password (min. 8 karakter)</label>
                <input class="form-control" type="password" id="password" name="password" required minlength="8" maxlength="72" autocomplete="new-password">
            </div>
            <div>
                <label class="field-label" for="confirm">Ulangi password</label>
                <input class="form-control" type="password" id="confirm" name="confirm" required autocomplete="new-password">
            </div>
            <button class="btn-pill" type="submit">Daftar</button>
            <p class="caption">Sudah punya akun? <a href="<?= site_url('login') ?>">Masuk</a></p>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
