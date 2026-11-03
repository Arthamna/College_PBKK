<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<div class="page-heading">
	<div>
		<h1><?php echo lang('Siakad.mahasiswa'); ?></h1>
		<p class="text-secondary"><?php echo lang('Siakad.list_intro'); ?></p>
	</div>
	<a class="btn btn-primary" href="<?php echo site_url('admin/mahasiswa/create'); ?>">+ <?php echo lang('Siakad.add_mahasiswa'); ?></a>
</div>
<section class="panel">
	<form class="search-form" action="<?php echo site_url('admin/mahasiswa'); ?>" method="get" role="search">
		<div class="search-input">
			<label class="form-label" for="q"><?php echo lang('Siakad.search_hint'); ?></label>
			<input class="form-control" type="search" id="q" name="q" value="<?php echo esc($kata_kunci, 'attr'); ?>">
		</div>
		<button class="btn btn-primary" type="submit"><?php echo lang('Siakad.search'); ?></button>
		<?php if ($kata_kunci !== ''): ?>
			<a class="btn btn-outline-secondary" href="<?php echo site_url('admin/mahasiswa'); ?>"><?php echo lang('Siakad.reset'); ?></a>
		<?php endif; ?>
	</form>
	<?php if ($mahasiswa === []): ?>
		<p class="empty-state"><?php echo lang($kata_kunci === '' ? 'Siakad.empty' : 'Siakad.empty_search'); ?></p>
	<?php else: ?>
		<div class="table-responsive">
			<table class="table align-middle mb-0">
				<thead><tr>
					<th scope="col"><?php echo lang('Siakad.nim'); ?></th>
					<th scope="col"><?php echo lang('Siakad.nama'); ?></th>
					<th scope="col"><?php echo lang('Siakad.program_studi'); ?></th>
					<th scope="col"><?php echo lang('Siakad.angkatan'); ?></th>
					<th scope="col"><?php echo lang('Siakad.email'); ?></th>
					<th scope="col"><?php echo lang('Siakad.actions'); ?></th>
				</tr></thead>
				<tbody>
					<?php foreach ($mahasiswa as $item): ?>
						<tr>
							<td class="text-nowrap"><?php echo esc($item['nim']); ?></td>
							<td class="fw-semibold"><?php echo esc($item['nama']); ?></td>
							<td><?php echo esc($item['program_studi']); ?></td>
							<td><?php echo esc($item['angkatan']); ?></td>
							<td><?php echo esc($item['email'] ?: '-'); ?></td>
							<td><div class="row-actions">
								<a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('admin/mahasiswa/' . $item['id'] . '/edit'); ?>"><?php echo lang('Siakad.edit'); ?></a>
								<form method="post" action="<?php echo site_url('admin/mahasiswa/' . $item['id'] . '/delete'); ?>" data-confirm="<?php echo esc(lang('Siakad.delete_confirm'), 'attr'); ?>">
									<?php echo csrf_field(); ?>
									<button type="submit" class="btn btn-sm btn-outline-danger"><?php echo lang('Siakad.delete'); ?></button>
								</form>
							</div></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<div class="panel-footer">
		<span class="text-secondary"><?php echo lang('Siakad.total_results', [$pager->getTotal('mahasiswa')]); ?></span>
		<?php echo $pager->only(['q'])->links('mahasiswa', 'siakad'); ?>
	</div>
</section>
<?php echo $this->endSection(); ?>
