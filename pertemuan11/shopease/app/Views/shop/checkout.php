<?php /** @var \App\Domain\CartSummary $cart */ $this->setVar('title', 'Checkout'); ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page">
    <h1 class="display-md">Checkout</h1>
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <form action="<?= site_url('checkout') ?>" method="post" id="checkout-form" class="stack">
                <?= csrf_field() ?>
                <h2 class="tagline">Alamat pengiriman</h2>
                <?php if ($addresses === []): ?>
                    <p class="muted">Belum ada alamat. Tambahkan alamat di bawah.</p>
                <?php endif ?>
                <?php foreach ($addresses as $i => $address): ?>
                    <label class="utility-card" style="display:block;cursor:pointer">
                        <input type="radio" name="address_id" value="<?= (int) $address['id'] ?>" <?= $i === 0 ? 'checked' : '' ?> required>
                        <strong><?= esc($address['label']) ?></strong> — <?= esc($address['recipient_name']) ?> (<?= esc($address['phone']) ?>)<br>
                        <span class="caption"><?= esc($address['street']) ?>, <?= esc($address['city']) ?>, <?= esc($address['province']) ?> <?= esc($address['postal_code']) ?></span>
                    </label>
                <?php endforeach ?>
                <div>
                    <label class="field-label" for="notes">Catatan (opsional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" maxlength="500"></textarea>
                </div>
            </form>

            <details style="margin-top:32px" <?= $addresses === [] ? 'open' : '' ?>>
                <summary class="tagline" style="cursor:pointer">Tambah alamat baru</summary>
                <form action="<?= site_url('addresses') ?>" method="post" class="stack" style="margin-top:16px">
                    <?= csrf_field() ?>
                    <?php
                    $fields = [
                        'label' => 'Label (mis. Rumah)', 'recipient_name' => 'Nama penerima', 'phone' => 'Telepon',
                        'street' => 'Alamat lengkap', 'city' => 'Kota', 'province' => 'Provinsi', 'postal_code' => 'Kode pos (5 digit)',
                    ];
                    foreach ($fields as $name => $label): ?>
                        <div>
                            <label class="field-label" for="a_<?= $name ?>"><?= esc($label) ?></label>
                            <input class="form-control" id="a_<?= $name ?>" name="<?= $name ?>" value="<?= esc(old($name)) ?>" required maxlength="<?= $name === 'postal_code' ? 5 : 100 ?>">
                        </div>
                    <?php endforeach ?>
                    <button class="btn-pearl" type="submit">Simpan alamat</button>
                </form>
            </details>
        </div>
        <div class="col-12 col-lg-5">
            <div class="utility-card">
                <h2 class="tagline">Ringkasan pesanan</h2>
                <?php foreach ($cart->lines as $line): ?>
                    <div class="summary-row"><span><?= esc($line['name']) ?> × <?= $line['quantity'] ?></span><span><?= rupiah($line['subtotal']) ?></span></div>
                <?php endforeach ?>
                <div class="summary-row"><span>Ongkos kirim</span><span><?= rupiah($cart->shipping) ?></span></div>
                <div class="summary-row total"><span>Total</span><span><?= rupiah($cart->total()) ?></span></div>
                <button class="btn-pill w-100" style="margin-top:16px" type="submit" form="checkout-form" <?= $addresses === [] ? 'disabled' : '' ?>>Buat pesanan</button>
                <p class="fine-print" style="margin-top:12px">Total dihitung ulang di server saat pesanan dibuat.</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
