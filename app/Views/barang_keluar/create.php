<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Catat Transaksi Barang Keluar</h1><a href="<?= base_url('barang-keluar') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="row"><div class="col-md-6">
    <?php if(session('validation')): ?><div class="alert alert-danger" role="alert"><?= session('validation')->listErrors() ?></div><?php endif; ?>
    <form action="<?= base_url('barang-keluar/save') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3"><label for="product_id" class="form-label">Nama Barang</label><select class="form-select" id="product_id" name="product_id" required><option value="">Pilih Barang...</option><?php foreach ($products as $p): ?><option value="<?= $p['id'] ?>" <?= old('product_id') == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?> (Stok: <?= $p['stock'] ?>)</option><?php endforeach; ?></select></div>
        <div class="mb-3"><label for="quantity" class="form-label">Jumlah Keluar</label><input type="number" class="form-control" id="quantity" name="quantity" value="<?= old('quantity') ?>" required min="1"></div>
        <div class="mb-3"><label for="notes" class="form-label">Catatan</label><textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea></div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div></div>
<?= $this->endSection() ?>