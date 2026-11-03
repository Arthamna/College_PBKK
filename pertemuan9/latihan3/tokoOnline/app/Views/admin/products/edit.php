<?php echo $this->extend('layouts/admin'); ?>
<?php echo $this->section('content'); ?>
<div class="page-heading"><a class="text-link" href="<?php echo site_url('admin/products'); ?>">← <?php echo lang('Shop.back_products'); ?></a><h1><?php echo lang('Shop.edit_product'); ?></h1><p class="muted"><?php echo lang('Shop.required_note'); ?></p></div>
<?php echo $this->include('admin/products/_form'); ?>
<?php echo $this->endSection(); ?>
