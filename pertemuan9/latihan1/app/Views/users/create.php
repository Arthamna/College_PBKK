<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Tambah Pengguna</h1><p>Buat akun untuk anggota tim.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('users/_form') ?>
<?= $this->endSection() ?>
