<?php $pager->setSurroundCount(2); ?>
<?php if ($pager->getPageCount() > 1): ?>
<nav aria-label="Navigasi halaman barang"><ul class="pagination">
    <?php if ($pager->hasPreviousPage()): ?><li class="page-item"><a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Halaman sebelumnya">‹</a></li><?php endif ?>
    <?php foreach ($pager->links() as $link): ?><li class="page-item <?= $link['active'] ? 'active' : '' ?>"><a class="page-link" href="<?= $link['uri'] ?>" <?= $link['active'] ? 'aria-current="page"' : '' ?>><?= $link['title'] ?></a></li><?php endforeach ?>
    <?php if ($pager->hasNextPage()): ?><li class="page-item"><a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Halaman berikutnya">›</a></li><?php endif ?>
</ul></nav>
<?php endif ?>
