<?php
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShipmentStatus;

$this->setVar('title', 'Pesanan ' . $order['order_number']);
$paymentStatus = PaymentStatus::from($payment['status']);
?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <p class="caption"><a href="<?= site_url('orders') ?>">‹ Semua pesanan</a></p>
    <div class="page-head">
        <h1 class="display-md" style="margin:0"><?= esc($order['order_number']) ?></h1>
        <?= status_chip($status) ?>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="table-wrap">
                <table class="table mb-0">
                    <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= esc($item['product_name']) ?></td>
                            <td><?= rupiah($item['unit_price']) ?></td>
                            <td><?= (int) $item['quantity'] ?></td>
                            <td><?= rupiah($item['subtotal']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="utility-card" style="margin-top:24px">
                <h2 class="tagline">Alamat pengiriman</h2>
                <p class="mb-0"><?= esc($address['recipient_name']) ?> (<?= esc($address['phone']) ?>)<br><?= esc($address['street']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?> <?= esc($address['postal_code']) ?></p>
            </div>
            <?php if ($shipment !== null): ?>
                <div class="utility-card" style="margin-top:24px">
                    <h2 class="tagline">Pengiriman</h2>
                    <p class="mb-0">Kurir: <?= esc($shipment['courier']) ?><br>No. resi: <strong><?= esc($shipment['tracking_number']) ?></strong><br>Status: <?= esc(ShipmentStatus::from($shipment['status'])->label()) ?></p>
                </div>
            <?php endif ?>
        </div>

        <div class="col-12 col-lg-5">
            <div class="utility-card">
                <h2 class="tagline">Pembayaran</h2>
                <div class="summary-row"><span>Subtotal</span><span><?= rupiah($order['subtotal']) ?></span></div>
                <div class="summary-row"><span>Ongkos kirim</span><span><?= rupiah($order['shipping_cost']) ?></span></div>
                <div class="summary-row total"><span>Total</span><span><?= rupiah($order['total']) ?></span></div>
                <p style="margin-top:12px">Status: <?= status_chip($paymentStatus) ?></p>
                <?php if ($paymentStatus === PaymentStatus::Rejected && ! empty($payment['rejection_reason'])): ?>
                    <p class="field-error">Ditolak: <?= esc($payment['rejection_reason']) ?></p>
                <?php endif ?>

                <?php if ($status === OrderStatus::PendingPayment && $paymentStatus->canUploadProof()): ?>
                    <p class="caption">Transfer <?= rupiah($order['total']) ?> ke <strong><?= esc($shop->bankName) ?></strong> <?= esc($shop->bankAccount) ?>, lalu unggah bukti transfer (JPG/PNG/WEBP, maks. <?= (int) ($shop->maxUploadKb / 1024) ?> MB).</p>
                    <form action="<?= site_url('orders/' . $order['id'] . '/payment') ?>" method="post" enctype="multipart/form-data" class="stack">
                        <?= csrf_field() ?>
                        <input class="form-control" type="file" name="proof" accept="image/jpeg,image/png,image/webp" required aria-label="Bukti pembayaran">
                        <button class="btn-pill" type="submit">Unggah bukti</button>
                    </form>
                <?php elseif (! empty($payment['proof_file'])): ?>
                    <a href="<?= site_url('media/proof/' . $order['id']) ?>" target="_blank" rel="noopener">Lihat bukti pembayaran</a>
                <?php endif ?>

                <?php if ($status === OrderStatus::PendingPayment): ?>
                    <form action="<?= site_url('orders/' . $order['id'] . '/cancel') ?>" method="post" style="margin-top:16px" onsubmit="return confirm('Batalkan pesanan ini?')">
                        <?= csrf_field() ?>
                        <button class="btn-pearl" type="submit">Batalkan pesanan</button>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
