<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Kategori</h1>
    <a href="<?= base_url('kategori') ?>" class="btn btn-secondary">Kembali</a>
</div>
<div class="row"><div class="col-md-6">
    <?php if(session('validation')): ?><div class="alert alert-danger" role="alert"><?= session('validation')->listErrors() ?></div><?php endif; ?>
    <form action="<?= base_url('kategori/update/' . $kategori['id']) ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="POST">
        <div class="mb-3"><label for="name" class="form-label">Nama Kategori</label><input type="text" class="form-control" id="name" name="name" value="<?= old('name', $kategori['name']) ?>" required></div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div></div>
<?= $this->endSection() ?>