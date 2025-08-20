<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Pembelian</h1><a href="<?= base_url('purchase') ?>" class="btn btn-secondary">Kembali</a>
</div>

<div class="row">
    <div class="col-md-6">
        <strong>Vendor:</strong><p><?= esc($purchase['vendor_name']) ?></p>
        <strong>Alamat:</strong><p><?= esc($purchase['address']) ?></p>
    </div>
    <div class="col-md-6">
        <strong>Tanggal:</strong><p><?= date('d F Y', strtotime($purchase['purchase_date'])) ?></p>
        <strong>Pembeli:</strong><p><?= esc($purchase['buyer_name']) ?></p>
        <strong>Status:</strong><p><span class="badge bg-<?= $purchase['status'] == 'Pending' ? 'warning' : 'success' ?>"><?= esc($purchase['status']) ?></span></p>
    </div>
</div>

<hr>
<h5>Rincian Barang</h5>
<table class="table">
    <thead><tr><th>Kode</th><th>Nama Barang</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead>
    <tbody>
        <?php foreach($details as $d): ?>
        <tr>
            <td><?= esc($d['code']) ?></td>
            <td><?= esc($d['product_name']) ?></td>
            <td><?= $d['quantity'] ?></td>
            <td>Rp <?= number_format($d['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($d['quantity'] * $d['price'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-end">Total</th>
            <th>Rp <?= number_format($purchase['total_amount'], 0, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>

<?php if($purchase['status'] == 'Pending'): ?>
<div class="mt-4 text-end">
    <form action="<?= base_url('barang-masuk/process') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="purchase_id" value="<?= $purchase['id'] ?>">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle-fill"></i> Proses sebagai Barang Masuk
        </button>
    </form>
</div>
<?php endif; ?>
<?= $this->endSection() ?>