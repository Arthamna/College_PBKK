<?php $editing = isset($user['id']); ?>
<div class="card form-card"><div class="card-body">
    <form action="<?= site_url($editing ? 'users/' . $user['id'] . '/update' : 'users') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="username" class="form-label">Username <span class="text-danger">*</span></label><input class="form-control" id="username" name="username" minlength="3" maxlength="50" autocomplete="off" value="<?= esc(old('username', $user['username'] ?? '', false)) ?>" required></div>
        <div class="mb-3"><label for="role" class="form-label">Role <span class="text-danger">*</span></label><select class="form-select" id="role" name="role" required><?php foreach (['staff' => 'Staff', 'admin' => 'Admin'] as $value => $label): ?><option value="<?= $value ?>" <?= old('role', $user['role'] ?? 'staff', false) === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select></div>
        <div class="mb-4"><label for="password" class="form-label">Password <?= $editing ? '' : '<span class="text-danger">*</span>' ?></label><input class="form-control" id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" aria-describedby="password-help" <?= $editing ? '' : 'required' ?>><div class="form-text" id="password-help">8–72 karakter. <?= $editing ? 'Kosongkan jika tidak ingin mengubah password.' : '' ?></div></div>
        <div class="d-flex flex-wrap gap-2"><button class="btn btn-primary" type="submit"><?= $editing ? 'Simpan Perubahan' : 'Simpan Pengguna' ?></button><a class="btn btn-outline-secondary" href="<?= site_url('users') ?>">Batal</a></div>
    </form>
</div></div>
