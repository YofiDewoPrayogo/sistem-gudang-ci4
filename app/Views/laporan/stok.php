<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Stok Barang Terkini</h1>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('laporan/stok') ?>" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama atau kode barang..." name="keyword" value="<?= esc($keyword) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
                <th scope="col">Kategori</th>
                <th scope="col">Satuan</th>
                <th scope="col">Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1 + (10 * ((request()->getGet('page_products') ?? 1) - 1)); ?>
            <?php foreach ($products as $p) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= esc($p['code']) ?></td>
                <td><?= esc($p['name']) ?></td>
                <td><?= esc($p['category_name']) ?></td>
                <td><?= esc($p['unit']) ?></td>
                <td><strong><?= esc($p['stock']) ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?= $pager->links('products', 'bootstrap_pager') ?>
</div>
<?= $this->endSection() ?>