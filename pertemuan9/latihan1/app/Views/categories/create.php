<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Tambah Kategori</h1><p>Buat kelompok untuk barang persediaan.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('categories/_form') ?>
<?= $this->endSection() ?>
