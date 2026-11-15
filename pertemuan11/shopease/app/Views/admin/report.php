<?php use App\Enums\OrderStatus; $this->setVar('title', 'Laporan penjualan'); ?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<form method="get" class="row g-3 align-items-end" style="margin-bottom:24px">
    <div class="col-6 col-md-3"><label class="field-label" for="from">Dari</label><input class="form-control" type="date" id="from" name="from" value="<?= esc($from) ?>"></div>
    <div class="col-6 col-md-3"><label class="field-label" for="to">Sampai</label><input class="form-control" type="date" id="to" name="to" value="<?= esc($to) ?>"></div>
    <div class="col-12 col-md-3">
        <label class="field-label" for="status">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="">Semua</option>
            <?php foreach (OrderStatus::cases() as $case): ?>
                <option value="<?= $case->value ?>" <?= $current === $case ? 'selected' : '' ?>><?= esc($case->label()) ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-12 col-md-3"><button class="btn-pill w-100" type="submit">Tampilkan</button></div>
</form>

<div class="stat-grid">
    <div class="stat"><div class="num"><?= rupiah($report['sales']) ?></div><div class="lbl">Penjualan terealisasi (diproses, dikirim, selesai)</div></div>
    <?php foreach ($report['byStatus'] as $row): ?>
        <div class="stat"><div class="num"><?= (int) $row['order_count'] ?></div><div class="lbl"><?= esc(OrderStatus::from($row['status'])->label()) ?> · <?= rupiah($row['amount']) ?></div></div>
    <?php endforeach ?>
</div>

<div class="table-wrap">
    <table class="table mb-0">
        <thead><tr><th>No. pesanan</th><th>Pelanggan</th><th>Tanggal</th><th>Status</th><th class="text-end">Total</th></tr></thead>
        <tbody>
        <?php foreach ($report['orders'] as $row): ?>
            <tr>
                <td><?= esc($row['order_number']) ?></td>
                <td><?= esc($row['customer_name']) ?></td>
                <td><?= esc(date('d M Y H:i', strtotime((string) $row['created_at']))) ?></td>
                <td><?= status_chip(OrderStatus::from($row['status'])) ?></td>
                <td class="text-end"><?= rupiah($row['total']) ?></td>
            </tr>
        <?php endforeach ?>
        <?php if ($report['orders'] === []): ?><tr><td colspan="5" class="muted">Tidak ada transaksi pada periode ini.</td></tr><?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
