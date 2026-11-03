<?php $pager->setSurroundCount(1); ?>
<?php if ($pager->getPageCount() > 1): ?>
	<nav aria-label="<?php echo lang('Siakad.pagination'); ?>">
		<ul class="pagination mb-0 flex-wrap">
			<?php if ($pager->hasPreviousPage()): ?>
				<li class="page-item"><a class="page-link" href="<?php echo $pager->getPreviousPage(); ?>"><?php echo lang('Siakad.previous'); ?></a></li>
			<?php endif; ?>
			<?php foreach ($pager->links() as $link): ?>
				<li class="page-item <?php echo $link['active'] ? 'active' : ''; ?>">
					<a class="page-link" href="<?php echo $link['uri']; ?>" <?php echo $link['active'] ? 'aria-current="page"' : ''; ?>><?php echo $link['title']; ?></a>
				</li>
			<?php endforeach; ?>
			<?php if ($pager->hasNextPage()): ?>
				<li class="page-item"><a class="page-link" href="<?php echo $pager->getNextPage(); ?>"><?php echo lang('Siakad.next'); ?></a></li>
			<?php endif; ?>
		</ul>
	</nav>
<?php endif; ?>
