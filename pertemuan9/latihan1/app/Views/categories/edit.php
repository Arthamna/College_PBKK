<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Edit Kategori</h1><p>Perbarui nama kategori.</p></div></div>
<?= $this->include('partials/alerts') ?>
<?= $this->include('categories/_form') ?>
<?= $this->endSection() ?>
