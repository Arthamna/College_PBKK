<?php
/** @var \CodeIgniter\Pager\PagerRenderer $pager */
$pager->setSurroundCount(2);
?>
<nav class="pager" aria-label="Navigasi halaman">
    <?php if ($pager->hasPreviousPage()): ?>
        <a href="<?= $pager->getPreviousPage() ?>" rel="prev">‹ Sebelumnya</a>
    <?php endif ?>
    <?php foreach ($pager->links() as $link): ?>
        <?php if ($link['active']): ?>
            <span class="active" aria-current="page"><?= $link['title'] ?></span>
        <?php else: ?>
            <a href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
        <?php endif ?>
    <?php endforeach ?>
    <?php if ($pager->hasNextPage()): ?>
        <a href="<?= $pager->getNextPage() ?>" rel="next">Berikutnya ›</a>
    <?php endif ?>
</nav>
