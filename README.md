# Sistem Manajemen Gudang Sederhana

Aplikasi web sederhana untuk manajemen gudang yang dibangun menggunakan framework CodeIgniter 4. Aplikasi ini memungkinkan admin untuk mengelola data master, mencatat transaksi barang masuk dan keluar, serta melihat laporan stok dan transaksi.

## Fitur Utama

Aplikasi ini memiliki beberapa fitur utama yang terorganisir dalam beberapa modul:

#### **Dashboard**
* Menampilkan ringkasan data secara visual.
* **Kartu Statistik**: Total Barang, Total Kategori, dan Total Transaksi Hari Ini.
* **Grafik Stok**: Visualisasi 5 barang dengan stok terbanyak menggunakan Chart.js.
* **Panel Stok Kritis**: Menampilkan daftar barang yang stoknya berada di bawah ambang batas (<= 10).
* **Aktivitas Terbaru**: Log 5 transaksi terakhir (barang masuk & keluar).

#### **Autentikasi & Manajemen Profil**
* Sistem login dan logout yang aman menggunakan session.
* Proteksi halaman menggunakan **Route Filter**, hanya user yang sudah login yang bisa mengakses data.
* Halaman **Edit Profil** untuk mengubah nama, username, dan password.

#### **Master Data**
* **Manajemen Kategori**: CRUD (Create, Read, Update, Delete) untuk kategori barang.
* **Manajemen Barang**: CRUD untuk data barang, termasuk kode, nama, kategori, satuan, dan stok awal.
* **Manajemen Vendor**: CRUD untuk data pemasok (vendor).

#### **Transaksi**
* **Alur Kerja Pembelian**: Membuat data pembelian dari vendor tertentu, lengkap dengan rincian barang, jumlah, dan harga.
* **Proses Barang Masuk**: Memproses data pembelian yang statusnya "Pending" untuk secara otomatis menambah stok barang dan mencatatnya di log barang masuk.
* **Barang Keluar**: Mencatat transaksi barang keluar dan secara otomatis mengurangi stok. Stok tidak bisa minus.

#### **Laporan**
* **Laporan Stok**: Menampilkan daftar semua barang beserta sisa stok terkini.
* **Laporan Barang Masuk**: Menampilkan log barang masuk berdasarkan filter rentang tanggal.
* **Laporan Barang Keluar**: Menampilkan log barang keluar berdasarkan filter rentang tanggal.

#### **Peningkatan UX (User Experience)**
* **Desain Responsif**: Tampilan yang menyesuaikan untuk perangkat desktop, tablet, dan mobile dengan *sidebar* yang bisa disembunyikan (burger menu).
* **Pencarian & Sortir**: Fungsionalitas pencarian di semua halaman data master dan laporan, serta sortir kolom pada tabel.
* **Paginasi**: Penomoran halaman untuk tabel data yang panjang.
* **Notifikasi Modern**: Menggunakan SweetAlert2 untuk konfirmasi hapus data yang lebih interaktif.


## Teknologi yang Digunakan

**Framework**: CodeIgniter 4.4.8
**Bahasa**: PHP 8.0+
**Database**: MySQL (dengan engine InnoDB)
**Frontend**: Bootstrap 5, Bootstrap Icons
**Library JavaScript**: Chart.js (untuk grafik), SweetAlert2 (untuk notifikasi)
**Dependency Manager**: Composer

## Petunjuk Instalasi & Setup

Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan lokal  .

## Prasyarat
* XAMPP atau sejenisnya (dengan PHP 8.0 atau lebih baru)
* Composer

## **Langkah-langkah Instalasi**

1.  **Clone Repositori**
    Buka terminal/CMD dan jalankan perintah berikut:
    ```bash
    git clone 
    cd nama-folder-proyek
    ```

2.  **Install Dependencies**
    Jalankan perintah Composer untuk menginstal semua library yang dibutuhkan.
    ```bash
    composer install
    ```

3.  **Konfigurasi Database**
    * Salin file `env` menjadi `.env`.
    * Buka file `.env` dan sesuaikan konfigurasi database  :
        ```env
        database.default.hostname = localhost
        database.default.database = db_gudang
        database.default.username = root
        database.default.password = 
        ```
    * Buat database baru di phpMyAdmin dengan nama `db_gudang`.

4.  **Import Database**
    Impor file `db_gudang.sql` yang sudah tersedia di repositori ini ke dalam database `db_gudang` yang baru saja  buat melalui phpMyAdmin.

5.  **Jalankan Aplikasi**
    Gunakan server pengembangan bawaan CodeIgniter melalui Spark.
    ```bash
    php spark serve
    ```

6.  **Selesai!**
    Buka browser dan kunjungi `http://localhost:8080`.

### **Akun Default**
* **Username**: `admin`
* **Password**: `admin123`

## Tantangan Selama Pengerjaan

* **Logika Transaksi Database**: Salah satu tantangan utama adalah memastikan proses barang masuk dan keluar berjalan dengan aman. Jika pencatatan log berhasil tetapi update stok gagal, data akan menjadi tidak konsisten. Solusinya adalah dengan menerapkan **Database Transaction** (`transStart`, `transComplete`).
* **Masalah *Engine* Database**: Sempat terjadi kegagalan transaksi yang sulit dilacak. Setelah debugging, ditemukan bahwa beberapa tabel menggunakan *engine* MyISAM yang tidak mendukung transaksi. Solusinya adalah mengubah semua *engine* tabel menjadi **InnoDB**.
* **Form Dinamis (JavaScript)**: Mengimplementasikan form pembelian dengan detail barang yang bisa ditambah-kurangi memerlukan logika JavaScript untuk memanipulasi DOM dan memastikan nama *input* dikirim sebagai *array* yang terstruktur dengan benar agar bisa diproses oleh PHP.