<?php /** @var \App\Domain\CartSummary $cart */ $this->setVar('title', 'Keranjang'); ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <h1 class="display-md">Keranjang</h1>
    <?php if ($cart->isEmpty()): ?>
        <div class="utility-card"><p>Keranjang Anda kosong.</p><a class="btn-pill" href="<?= site_url('products') ?>">Mulai belanja</a></div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="table-wrap">
                    <table class="table mb-0">
                        <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($cart->lines as $line): ?>
                            <tr>
                                <td><a href="<?= site_url('products/' . $line['slug']) ?>"><?= esc($line['name']) ?></a><br><span class="fine-print">Stok: <?= $line['stock'] ?></span></td>
                                <td><?= rupiah($line['unit_price']) ?></td>
                                <td>
                                    <form class="inline-form" action="<?= site_url('cart/update/' . $line['product_id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input class="form-control qty-input" type="number" name="quantity" value="<?= $line['quantity'] ?>" min="1" max="<?= max(1, $line['stock']) ?>" aria-label="Kuantitas <?= esc($line['name']) ?>">
                                        <button class="btn-pearl" type="submit">Ubah</button>
                                    </form>
                                </td>
                                <td><?= rupiah($line['subtotal']) ?></td>
                                <td>
                                    <form action="<?= site_url('cart/remove/' . $line['product_id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button class="btn-pearl" type="submit" aria-label="Hapus <?= esc($line['name']) ?>">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="utility-card">
                    <h2 class="tagline">Ringkasan</h2>
                    <div class="summary-row"><span>Subtotal</span><span><?= rupiah($cart->subtotal) ?></span></div>
                    <div class="summary-row"><span>Ongkos kirim</span><span><?= rupiah($cart->shipping) ?></span></div>
                    <div class="summary-row total"><span>Total</span><span><?= rupiah($cart->total()) ?></span></div>
                    <a class="btn-pill w-100" style="margin-top:16px" href="<?= site_url('checkout') ?>">Checkout</a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
<?= $this->endSection() ?>
