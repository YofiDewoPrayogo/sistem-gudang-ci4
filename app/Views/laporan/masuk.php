<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Barang Masuk</h1>
</div>

<div class="card mb-4">
    <div class="card-header">
        Filter Laporan
    </div>
    <div class="card-body">
        <form action="<?= base_url('laporan/masuk') ?>" method="get">
            <div class="row">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= esc($startDate) ?>" required>
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= esc($endDate) ?>" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal Masuk</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Jumlah Masuk</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($items)): ?>
            <?php $i = 1; ?>
            <?php foreach ($items as $item) : ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= date('d M Y H:i', strtotime($item['incoming_date'])) ?></td>
                <td><?= esc($item['code']) ?></td>
                <td><?= esc($item['product_name']) ?></td>
                <td><?= esc($item['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Silakan pilih rentang tanggal untuk menampilkan data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>