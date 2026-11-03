<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Tambah Barang</h1><p>Lengkapi data barang baru.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('products/_form') ?>
<?= $this->endSection() ?>
