<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-header"><div><h1>Pengguna</h1><p>Kelola akun dan hak akses Admin atau Staff.</p></div><a class="btn btn-primary" href="<?= site_url('users/create') ?>">+ Tambah Pengguna</a></div>
<?= $this->include('partials/alerts') ?>
<div class="card"><div class="table-responsive">
    <table class="table table-hover"><caption class="visually-hidden">Daftar pengguna aplikasi</caption>
        <thead><tr><th class="ps-4">No.</th><th>Username</th><th>Role</th><th class="pe-4">Aksi</th></tr></thead>
        <tbody><?php foreach ($users as $index => $user): ?><tr>
            <td class="ps-4"><?= $index + 1 ?></td><td class="fw-semibold"><?= esc($user['username']) ?><?php if ((int) $user['id'] === (int) session()->get('user_id')): ?> <span class="badge bg-light text-dark">Anda</span><?php endif ?></td>
            <td><span class="badge <?= $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary' ?>"><?= esc(ucfirst($user['role'])) ?></span></td>
            <td class="pe-4"><div class="action-buttons"><a class="btn btn-sm btn-outline-primary" href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a><form method="post" action="<?= site_url('users/' . $user['id'] . '/delete') ?>" data-confirm="Hapus akun pengguna ini?"><?= csrf_field() ?><button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button></form></div></td>
        </tr><?php endforeach ?></tbody>
    </table>
</div></div>
<?= $this->endSection() ?>
