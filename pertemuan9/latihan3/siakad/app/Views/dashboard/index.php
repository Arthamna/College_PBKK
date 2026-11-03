<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<div class="page-heading">
	<div>
		<p class="eyebrow"><?php echo lang('Siakad.dashboard'); ?></p>
		<h1><?php echo esc(lang('Siakad.welcome', [session()->get('nama')])); ?></h1>
		<p class="text-secondary"><?php echo lang('Siakad.dashboard_intro'); ?></p>
	</div>
	<a class="btn btn-primary" href="<?php echo site_url('admin/mahasiswa/create'); ?>">+ <?php echo lang('Siakad.add_mahasiswa'); ?></a>
</div>
<section class="stat-card mb-4" aria-label="<?php echo lang('Siakad.total_mahasiswa'); ?>">
	<div>
		<p class="text-secondary mb-1"><?php echo lang('Siakad.total_mahasiswa'); ?></p>
		<strong class="stat-number"><?php echo esc((string) $total_mahasiswa); ?></strong>
	</div>
	<a href="<?php echo site_url('admin/mahasiswa'); ?>"><?php echo lang('Siakad.view_all'); ?> &rarr;</a>
</section>
<section class="panel">
	<div class="panel-heading"><h2><?php echo lang('Siakad.recent_mahasiswa'); ?></h2></div>
	<?php if ($mahasiswa_terbaru === []): ?>
		<p class="empty-state"><?php echo lang('Siakad.empty'); ?></p>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table align-middle mb-0">
				<thead><tr>
					<th scope="col"><?php echo lang('Siakad.nim'); ?></th>
					<th scope="col"><?php echo lang('Siakad.nama'); ?></th>
					<th scope="col"><?php echo lang('Siakad.program_studi'); ?></th>
					<th scope="col"><?php echo lang('Siakad.angkatan'); ?></th>
				</tr></thead>
				<tbody>
					<?php foreach ($mahasiswa_terbaru as $item): ?>
						<tr>
							<td><?php echo esc($item['nim']); ?></td>
							<td><a href="<?php echo site_url('admin/mahasiswa/' . $item['id'] . '/edit'); ?>"><?php echo esc($item['nama']); ?></a></td>
							<td><?php echo esc($item['program_studi']); ?></td>
							<td><?php echo esc($item['angkatan']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</section>
<?php echo $this->endSection(); ?>
