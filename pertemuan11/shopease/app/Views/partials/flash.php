<?php $errors = session()->getFlashdata('errors'); ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success" role="status"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif ?>
<?php if (is_array($errors) && $errors !== []): ?>
    <div class="flash flash-error" role="alert">
        <ul><?php foreach ($errors as $message): ?><li><?= esc($message) ?></li><?php endforeach ?></ul>
    </div>
<?php endif ?>
