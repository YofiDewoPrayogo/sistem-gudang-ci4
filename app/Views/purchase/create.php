<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buat Transaksi Pembelian Baru</h1><a href="<?= base_url('purchase') ?>" class="btn btn-secondary">Kembali</a>
</div>

<?php if(session('validation')): ?><div class="alert alert-danger" role="alert"><?= session('validation')->listErrors() ?></div><?php endif; ?>
<form action="<?= base_url('purchase') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="vendor_id" class="form-label">Vendor</label>
            <select class="form-select" name="vendor_id" required><option value="">Pilih Vendor...</option><?php foreach($vendors as $v): ?><option value="<?= $v['id'] ?>"><?= esc($v['name']) ?></option><?php endforeach; ?></select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
            <input type="date" class="form-control" name="purchase_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="buyer_name" class="form-label">Nama Pembeli</label>
            <input type="text" class="form-control" name="buyer_name" value="<?= old('buyer_name', session()->get('name')) ?>" required>
        </div>
    </div>

    <hr>
    <h5>Detail Barang</h5>
    <table class="table" id="product-table">
        <thead><tr><th>Produk</th><th>Jumlah</th><th>Harga Satuan</th><th>Aksi</th></tr></thead>
        <tbody></tbody>
    </table>
    <button type="button" class="btn btn-success" id="add-product-btn"><i class="bi bi-plus"></i> Tambah Produk</button>
    <hr>
    <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary">Simpan Transaksi</button></div>
</form>

<template id="product-row-template">
    <tr>
        <td>
            <select class="form-select product-select" name="product_id" required>
                <option value="">Pilih Produk...</option>
                <?php foreach($products as $p): ?>
                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" class="form-control" name="quantity" required min="1" value="1"></td>
        <td><input type="number" class="form-control" name="price" required min="0" value="0"></td>
        <td><button type="button" class="btn btn-danger remove-product-btn"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addProductBtn = document.getElementById('add-product-btn');
    const productTableBody = document.querySelector('#product-table tbody');
    const productRowTemplate = document.getElementById('product-row-template');
    let rowIndex = 0; // Tambahkan variabel counter untuk indeks baris

    addProductBtn.addEventListener('click', function() {
        // Ambil konten dari template
        const newRow = productRowTemplate.content.cloneNode(true);
        
        // Cari semua input dan select di dalam baris baru
        const inputs = newRow.querySelectorAll('select, input');

        // Ganti nama setiap input dengan menambahkan indeks baris yang unik
        inputs.forEach(input => {
            let currentName = input.getAttribute('name');
            if (currentName) {
                input.setAttribute('name', `products[${rowIndex}][${currentName}]`);
            }
        });

        productTableBody.appendChild(newRow);
        rowIndex++; // Naikkan indeks untuk baris berikutnya
    });

    productTableBody.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.remove-product-btn')) {
            e.target.closest('tr').remove();
        }
    });

    // Tambahkan baris pertama secara otomatis saat halaman dimuat
    addProductBtn.click();
});
</script>
<?= $this->endSection() ?>