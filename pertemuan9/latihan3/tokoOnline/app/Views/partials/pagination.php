<?php $pager->setSurroundCount(2); ?>
<?php if ($pager->getPageCount() > 1): ?>
<nav class="pagination-nav" aria-label="Navigasi halaman">
	<ul class="pagination-list">
		<?php if ($pager->hasPreviousPage()): ?>
			<li><a href="<?php echo esc($pager->getPreviousPage(), 'attr'); ?>" rel="prev" aria-label="Halaman sebelumnya">←</a></li>
		<?php endif; ?>
		<?php foreach ($pager->links() as $link): ?>
			<li><a href="<?php echo esc($link['uri'], 'attr'); ?>" <?php if ($link['active']): ?>aria-current="page"<?php endif; ?>><?php echo $link['title']; ?></a></li>
		<?php endforeach; ?>
		<?php if ($pager->hasNextPage()): ?>
			<li><a href="<?php echo esc($pager->getNextPage(), 'attr'); ?>" rel="next" aria-label="Halaman berikutnya">→</a></li>
		<?php endif; ?>
	</ul>
</nav>
<?php endif; ?>
