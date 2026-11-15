<?php
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShipmentStatus;

$this->setVar('title', 'Pesanan ' . $order['order_number']);
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<p class="caption"><a href="<?= site_url('admin/orders') ?>">‹ Semua pesanan</a></p>
<div class="page-head">
    <div><span class="fine-print">Pelanggan</span><br><strong><?= esc($order['customer_name']) ?></strong> · <?= esc($order['customer_email']) ?></div>
    <div><?= status_chip($status) ?> <?= status_chip($paymentStatus) ?></div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="table-wrap">
            <table class="table mb-0">
                <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr><td><?= esc($item['product_name']) ?></td><td><?= rupiah($item['unit_price']) ?></td><td><?= (int) $item['quantity'] ?></td><td><?= rupiah($item['subtotal']) ?></td></tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="utility-card" style="margin-top:24px">
            <h2 class="tagline">Alamat</h2>
            <p class="mb-0"><?= esc($address['recipient_name']) ?> (<?= esc($address['phone']) ?>)<br><?= esc($address['street']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?> <?= esc($address['postal_code']) ?></p>
            <?php if (! empty($order['notes'])): ?><p class="caption" style="margin-top:8px">Catatan: <?= esc($order['notes']) ?></p><?php endif ?>
        </div>
    </div>

    <div class="col-12 col-lg-5 stack">
        <div class="utility-card">
            <h2 class="tagline">Pembayaran</h2>
            <div class="summary-row total" style="border:0;margin:0;padding:0"><span>Total</span><span><?= rupiah($order['total']) ?></span></div>
            <?php if (! empty($order['proof_file'])): ?>
                <p style="margin-top:12px"><a href="<?= site_url('media/proof/' . $order['id']) ?>" target="_blank" rel="noopener">Lihat bukti pembayaran</a></p>
            <?php else: ?>
                <p class="muted" style="margin-top:12px">Belum ada bukti pembayaran.</p>
            <?php endif ?>
            <?php if (! empty($order['rejection_reason'])): ?><p class="field-error">Alasan penolakan: <?= esc($order['rejection_reason']) ?></p><?php endif ?>

            <?php if ($isAdmin && $paymentStatus === PaymentStatus::WaitingVerification): ?>
                <form action="<?= site_url('admin/orders/' . $order['id'] . '/verify') ?>" method="post" style="margin-top:12px">
                    <?= csrf_field() ?>
                    <button class="btn-pill" type="submit">Verifikasi pembayaran</button>
                </form>
                <form action="<?= site_url('admin/orders/' . $order['id'] . '/reject') ?>" method="post" class="stack" style="margin-top:12px">
                    <?= csrf_field() ?>
                    <input class="form-control" name="reason" placeholder="Alasan penolakan" required minlength="3" maxlength="255" aria-label="Alasan penolakan">
                    <button class="btn-pill-ghost" type="submit">Tolak pembayaran</button>
                </form>
            <?php elseif (! $isAdmin && $paymentStatus === PaymentStatus::WaitingVerification): ?>
                <p class="fine-print" style="margin-top:12px">Hanya administrator yang dapat memverifikasi pembayaran.</p>
            <?php endif ?>
        </div>

        <div class="utility-card">
            <h2 class="tagline">Pengiriman</h2>
            <?php if ($shipment !== null): ?>
                <p>Kurir: <?= esc($shipment['courier']) ?><br>No. resi: <strong><?= esc($shipment['tracking_number']) ?></strong><br>Status: <?= esc(ShipmentStatus::from($shipment['status'])->label()) ?></p>
                <?php if ($status === OrderStatus::Shipped): ?>
                    <form action="<?= site_url('admin/orders/' . $order['id'] . '/delivered') ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn-pill" type="submit">Tandai terkirim</button>
                    </form>
                <?php endif ?>
            <?php elseif ($status === OrderStatus::Processing): ?>
                <form action="<?= site_url('admin/orders/' . $order['id'] . '/ship') ?>" method="post" class="stack">
                    <?= csrf_field() ?>
                    <select class="form-select" name="courier" required aria-label="Kurir">
                        <?php foreach ($couriers as $courier): ?><option><?= esc($courier) ?></option><?php endforeach ?>
                    </select>
                    <input class="form-control" name="tracking_number" placeholder="Nomor resi" required minlength="5" maxlength="60" aria-label="Nomor resi">
                    <button class="btn-pill" type="submit">Kirim pesanan</button>
                </form>
            <?php else: ?>
                <p class="muted mb-0">Pengiriman tersedia setelah pembayaran terverifikasi.</p>
            <?php endif ?>
        </div>

        <?php if ($isAdmin && in_array($status, [OrderStatus::PendingPayment, OrderStatus::WaitingVerification, OrderStatus::Processing], true)): ?>
            <form action="<?= site_url('admin/orders/' . $order['id'] . '/cancel') ?>" method="post" onsubmit="return confirm('Batalkan pesanan dan kembalikan stok?')">
                <?= csrf_field() ?>
                <button class="btn-pearl" type="submit">Batalkan pesanan</button>
            </form>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
