<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Barang</h1><a href="<?= base_url('barang') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="row"><div class="col-md-6">
    <?php if(session('validation')): ?><div class="alert alert-danger" role="alert"><?= session('validation')->listErrors() ?></div><?php endif; ?>
    <form action="<?= base_url('barang/update/' . $product['id']) ?>" method="post">
        <?= csrf_field() ?><input type="hidden" name="_method" value="PUT">
        <div class="mb-3"><label for="name" class="form-label">Nama Barang</label><input type="text" class="form-control" id="name" name="name" value="<?= old('name', $product['name']) ?>" required></div>
        <div class="mb-3"><label for="code" class="form-label">Kode Barang</label><input type="text" class="form-control" id="code" name="code" value="<?= old('code', $product['code']) ?>" required></div>
        <div class="mb-3"><label for="category_id" class="form-label">Kategori</label><select class="form-select" id="category_id" name="category_id" required><option value="">Pilih Kategori...</option><?php foreach ($kategori as $k): ?><option value="<?= $k['id'] ?>" <?= (old('category_id', $product['category_id']) == $k['id']) ? 'selected' : '' ?>><?= esc($k['name']) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label for="unit" class="form-label">Satuan</label><input type="text" class="form-control" id="unit" name="unit" value="<?= old('unit', $product['unit']) ?>" required></div>
        <div class="mb-3"><label for="stock" class="form-label">Stok</label><input type="number" class="form-control" id="stock" name="stock" value="<?= old('stock', $product['stock']) ?>" required></div>
        <button type="submit" class="btn btn-primary">Update Barang</button>
    </form>
</div></div>
<?= $this->endSection() ?>