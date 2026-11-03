<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Edit Barang</h1><p>Perbarui data <?= esc($product['name']) ?>.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('products/_form') ?>
<?= $this->endSection() ?>
