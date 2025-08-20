<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-cart-fill"></i> Data Pembelian</h1>
    <a href="<?= base_url('purchase/new') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Pembelian</a>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark"><tr><th>#</th><th>Tanggal</th><th>Vendor</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php $i = 1; foreach ($purchases as $p) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= date('d M Y', strtotime($p['purchase_date'])) ?></td>
                <td><?= esc($p['vendor_name']) ?></td>
                <td><?= esc($p['buyer_name']) ?></td>
                <td>Rp <?= number_format($p['total_amount'], 0, ',', '.') ?></td>
                <td>
                    <span class="badge bg-<?= $p['status'] == 'Pending' ? 'warning' : 'success' ?>">
                        <?= esc($p['status']) ?>
                    </span>
                </td>
                <td><a href="<?= base_url('purchase/' . $p['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-eye-fill"></i></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $pager->links('purchases', 'bootstrap_pager') ?>
</div>
<?= $this->endSection() ?>