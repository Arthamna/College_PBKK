<aside class="sidebar">
	<a class="brand" href="<?php echo site_url('admin'); ?>">
		<span class="brand-icon" aria-hidden="true">S</span>
		<span>SIAKAD<small><?php echo lang('Siakad.app_description'); ?></small></span>
	</a>
	<p class="nav-caption"><?php echo lang('Siakad.admin_panel'); ?></p>
	<nav aria-label="<?php echo lang('Siakad.admin_panel'); ?>">
		<a class="nav-item <?php echo $active_menu === 'dashboard' ? 'active' : ''; ?>"
			href="<?php echo site_url('admin'); ?>" <?php echo $active_menu === 'dashboard' ? 'aria-current="page"' : ''; ?>>
			<?php echo lang('Siakad.dashboard'); ?>
		</a>
		<a class="nav-item <?php echo $active_menu === 'mahasiswa' ? 'active' : ''; ?>"
			href="<?php echo site_url('admin/mahasiswa'); ?>" <?php echo $active_menu === 'mahasiswa' ? 'aria-current="page"' : ''; ?>>
			<?php echo lang('Siakad.mahasiswa'); ?>
		</a>
	</nav>
</aside>
