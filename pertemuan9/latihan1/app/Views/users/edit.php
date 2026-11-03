<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Edit Pengguna</h1><p>Perbarui akun <?= esc($user['username']) ?>.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('users/_form') ?>
<?= $this->endSection() ?>
