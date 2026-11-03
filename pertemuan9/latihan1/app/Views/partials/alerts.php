<?php if ($message = session()->getFlashdata('success')): ?>
    <div class="alert alert-success" role="status"><?= esc($message) ?></div>
<?php endif ?>
<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" role="alert"><?= esc($message) ?></div>
<?php endif ?>
<?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Periksa kembali isian berikut:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
