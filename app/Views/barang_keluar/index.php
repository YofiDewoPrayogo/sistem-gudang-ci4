<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-box-arrow-up"></i> Data Barang Keluar</h1>
    <a href="<?= base_url('barang-keluar/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Barang Keluar</a>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('barang-keluar') ?>" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama atau kode barang..." name="keyword" value="<?= esc($keyword) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead><tr><th>#</th><th>Tanggal</th><th>Kode Barang</th><th>Nama Barang</th><th>Jumlah Keluar</th><th>Catatan</th></tr></thead>
        <tbody>
            <?php $i = 1; foreach ($items as $item) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= date('d M Y H:i', strtotime($item['outgoing_date'])) ?></td>
                <td><?= esc($item['code']) ?></td>
                <td><?= esc($item['product_name']) ?></td>
                <td><?= esc($item['quantity']) ?></td>
                <td><?= esc($item['notes']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $pager->links('outgoing_items', 'bootstrap_pager') ?>
</div>
<?= $this->endSection() ?>