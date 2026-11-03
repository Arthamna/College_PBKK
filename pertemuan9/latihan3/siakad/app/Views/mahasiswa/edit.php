<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<div class="page-heading">
	<div>
		<h1><?php echo lang('Siakad.edit_mahasiswa'); ?></h1>
		<p class="text-secondary"><?php echo lang('Siakad.form_intro'); ?></p>
	</div>
	<a href="<?php echo site_url('admin/mahasiswa'); ?>"><?php echo lang('Siakad.back'); ?></a>
</div>
<form class="panel form-panel" method="post" action="<?php echo site_url('admin/mahasiswa/' . $mahasiswa['id'] . '/update'); ?>">
	<?php echo view('mahasiswa/_form', ['mahasiswa' => $mahasiswa]); ?>
</form>
<?php echo $this->endSection(); ?>
