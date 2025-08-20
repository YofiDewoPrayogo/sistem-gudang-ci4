<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-tags"></i> Daftar Kategori</h1>
    <a href="<?= base_url('kategori/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kategori</a>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('kategori') ?>" method="get">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama kategori..." name="keyword" value="<?= esc($keyword) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>
                    <?php
                        // Logika untuk menentukan urutan sortir selanjutnya
                        $nextOrder = ($sort == 'name' && $order == 'asc') ? 'desc' : 'asc';
                    ?>
                    <a href="<?= base_url('kategori') ?>?sort=name&order=<?= $nextOrder ?>&keyword=<?= esc($keyword) ?>" class="text-white text-decoration-none">
                        Nama Kategori
                        <?php if ($sort == 'name'): ?>
                            <i class="bi bi-arrow-<?= $order == 'asc' ? 'up' : 'down' ?>"></i>
                        <?php endif; ?>
                    </a>
                </th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Mengatur nomor urut agar sesuai dengan halaman paginasi
                $page = (request()->getGet('page_categories')) ? request()->getGet('page_categories') : 1;
                $i = 1 + (10 * ($page - 1));
            ?>
            <?php foreach ($kategori as $k) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= esc($k['name']) ?></td>
                <td>
                    <a href="<?= base_url('kategori/edit/' . $k['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                    <form action="<?= base_url('kategori/delete/' . $k['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?><input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="confirmDelete(event)"><i class="bi bi-trash3"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?= $pager->links('categories', 'bootstrap_pager') ?>
</div>
<?= $this->endSection() ?>