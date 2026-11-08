<?= $this->extend('shop/layout') ?>
<?= $this->section('content') ?>
<div class="card mx-auto p-4" style="max-width:460px"><h1 class="h3"><?= $register?'Daftar akun':'Login' ?></h1><form method="post" action="<?= site_url($register?'register':'login') ?>"><?= csrf_field() ?>
<?php if ($register): ?><label for="name">Nama</label><input class="form-control mb-3" id="name" name="name" required maxlength="255" value="<?= esc(old('name')) ?>"><?php endif ?>
<label for="email">Email</label><input class="form-control mb-3" type="email" id="email" name="email" required value="<?= esc(old('email')) ?>"><label for="password">Password<?= $register?' (minimal 8 karakter)':'' ?></label><input class="form-control mb-3" type="password" id="password" name="password" required <?= $register?'minlength="8"':'' ?> autocomplete="<?= $register?'new-password':'current-password' ?>"><button class="btn btn-primary"><?= $register?'Daftar':'Login' ?></button></form></div>
<?= $this->endSection() ?>
