<?= $this->extend('shop/layout') ?>
<?= $this->section('content') ?>
<h1>Pesanan</h1><div class="table-responsive"><table class="table"><thead><tr><th>Nomor</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead><tbody><?php foreach ($orders as $o): ?><tr><td><a href="<?= site_url(($user['role']==='customer'?'orders/':'admin/orders/').$o['id']) ?>"><?= esc($o['order_number']) ?></a></td><td><?= esc($o['created_at']) ?></td><td>Rp <?= esc($o['total']) ?></td><td><?= esc($o['order_status']) ?></td></tr><?php endforeach ?></tbody></table></div><?php if (!$orders): ?><p>Belum ada pesanan.</p><?php endif ?>
<?= $this->endSection() ?>
