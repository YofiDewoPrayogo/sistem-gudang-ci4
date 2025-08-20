<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-box-seam"></i> Data Barang</h1>
    <a href="<?= base_url('barang/new') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
</div>
<div class="row mb-3"><div class="col-md-6">
    <form action="<?= base_url('barang') ?>" method="get">
        <div class="input-group"><input type="text" class="form-control" placeholder="Cari nama atau kode barang..." name="keyword" value="<?= esc($keyword) ?>"><button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button></div>
    </form>
</div></div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark"><tr><th>#</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php $i = 1 + (10 * ((request()->getGet('page_products') ?? 1) - 1)); foreach ($products as $p) : ?>
            <tr>
                <td><?= $i++ ?></td><td><?= esc($p['code']) ?></td><td><?= esc($p['name']) ?></td><td><?= esc($p['category_name']) ?></td><td><?= esc($p['unit']) ?></td><td><strong><?= esc($p['stock']) ?></strong></td>
                <td>
                    <a href="<?= base_url('barang/edit/' . $p['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <form action="<?= base_url('barang/delete/' . $p['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event)"><i class="bi bi-trash3"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $pager->links('products', 'bootstrap_pager') ?>
</div>
<?= $this->endSection() ?>