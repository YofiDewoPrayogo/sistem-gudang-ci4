<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
</div>

<div class="alert alert-light" role="alert">Selamat datang kembali, <strong><?= esc(session()->get('name')) ?></strong>!</div>

<!-- Kartu Ringkasan -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Total Barang</h5><p class="card-text fs-4 fw-bold"><?= esc($totalBarang) ?></p></div><i class="bi bi-box-seam fs-1 opacity-50"></i></div></div></div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Total Kategori</h5><p class="card-text fs-4 fw-bold"><?= esc($totalKategori) ?></p></div><i class="bi bi-tags fs-1 opacity-50"></i></div></div></div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Transaksi Hari Ini</h5><p class="card-text fs-4 fw-bold"><?= esc($totalTransaksiHariIni) ?></p></div><i class="bi bi-arrow-down-up fs-1 opacity-50"></i></div></div></div>
    </div>
</div>

<!-- Baris untuk Chart dan Stok Kritis -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header">Grafik Stok 5 Barang Teratas</div>
            <div class="card-body"><canvas id="stokChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-exclamation-triangle-fill"></i> Stok Kritis (<= 10)
            </div>
            <div class="card-body p-2">
                <?php if (!empty($stokKritis)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($stokKritis as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= esc($item['name']) ?>
                                <span class="badge bg-danger rounded-pill"><?= esc($item['stock']) . ' ' . esc($item['unit']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-success mb-0 mx-2"><i class="bi bi-check-circle-fill"></i> Stok aman.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Baris untuk Aktivitas Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Tanggal</th><th>Nama Barang</th><th>Jumlah</th><th>Tipe</th></tr></thead>
                        <tbody>
                            <?php if (!empty($aktivitasTerbaru)): ?>
                                <?php foreach($aktivitasTerbaru as $aktivitas): ?>
                                <tr>
                                    <td><?= date('d M Y, H:i', strtotime($aktivitas['date'])) ?></td>
                                    <td><?= esc($aktivitas['name']) ?></td>
                                    <td><?= esc($aktivitas['quantity']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $aktivitas['type'] == 'MASUK' ? 'success' : 'danger' ?>">
                                            <?= esc($aktivitas['type']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">Belum ada aktivitas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const ctx = document.getElementById('stokChart').getContext('2d');
    const namaBarang = <?= $chartLabels ?? '[]' ?>;
    const jumlahStok = <?= $chartData ?? '[]' ?>;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: namaBarang,
            datasets: [{
                label: 'Jumlah Stok',
                data: jumlahStok,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
</script>
<?= $this->endSection() ?>