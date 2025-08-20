<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Sistem Gudang' ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
    }
    .sidebar {
        width: 260px;
        min-height: 100vh;
        background-color: #ffffff;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 60px;
        transition: all 0.3s;
        z-index: 1030; 
        
    }
     .sidebar-header {
    padding: 1.5rem 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
     }
    .submenu .nav-link {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
    }
    .dropdown-menu {
    transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    opacity: 0;
    visibility: hidden;
    display: block; /* Paksa tampil agar transisi bekerja */
    transform: translateY(10px); /* Mulai dari 10px di bawah */
    }
    .dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0); /* Kembali ke posisi normal */
    }
    .main-wrapper { 
        margin-left: 260px; 
        width: calc(100% - 260px);
        transition: all 0.3s;
    }
    .top-navbar { 
        height: 60px;
        /* --- PERBAIKAN DI SINI --- */
        position: relative; /* Diperlukan agar z-index berfungsi */
        z-index: 1031;      /* Nilai lebih tinggi dari sidebar */
    }
    .content { 
        padding: 20px; 
        min-height: calc(100vh - 60px); 
    }

    @media (max-width: 991.98px) {
        .sidebar { left: -260px; }
        .sidebar.active { left: 0; }
        .main-wrapper { margin-left: 0; width: 100%; }
    }
</style>
</head>
<body>

<div class="sidebar">
    <a href="<?= base_url('/') ?>" class="sidebar-header text-decoration-none">
    <i class="bi bi-box-seam-fill"></i> <span>GudangKu</span></a><hr class="mx-3 mt-0">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/') ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#masterDataSubmenu" role="button" aria-expanded="false" aria-controls="masterDataSubmenu">
                <i class="bi bi-database-fill"></i> Master Data
            </a>
            <div class="collapse submenu" id="masterDataSubmenu">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('kategori') ?>">Kategori Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('barang') ?>">Data Barang</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('vendor') ?>">Data Vendor</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#transaksiSubmenu" role="button" aria-expanded="false" aria-controls="transaksiSubmenu">
                <i class="bi bi-receipt"></i> Transaksi
            </a>
            <div class="collapse submenu" id="transaksiSubmenu">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('purchase') ?>">Pembelian</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('barang-masuk') ?>">Log Barang Masuk</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('barang-keluar') ?>">Log Barang Keluar</a></li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#laporanSubmenu" role="button" aria-expanded="false" aria-controls="laporanSubmenu">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
            </a>
            <div class="collapse submenu" id="laporanSubmenu">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('laporan/stok') ?>">Laporan Stok</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('laporan/masuk') ?>">Laporan Barang Masuk</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('laporan/keluar') ?>">Laporan Barang Keluar</a></li>
                </ul>
            </div>
        </li>
    </ul>
</div>

<div class="main-wrapper">
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm top-navbar">
        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?= esc(session()->get('name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="content">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script untuk SweetAlert konfirmasi hapus
    function confirmDelete(event) {
        event.preventDefault(); 
        const form = event.target.form; 
        Swal.fire({
            title: 'Apakah Anda yakin?', text: "Data yang dihapus tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { form.submit(); }
        });
    }

    // Script untuk menjaga menu aktif tetap terbuka dan ter-highlight
    document.addEventListener("DOMContentLoaded", function() {
        const currentUrl = window.location.href;
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active'); // Tambah/hapus kelas 'active'
        });
        
        navLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add('active');
                
                // Jika ini adalah link di dalam submenu, buka parent menu-nya
                const submenu = link.closest('.submenu');
                if (submenu) {
                    const parentToggle = document.querySelector(`a[href="#${submenu.id}"]`);
                    if (parentToggle) {
                        parentToggle.setAttribute('aria-expanded', 'true');
                        submenu.classList.add('show');
                    }
                }
            }
        });
    });
</script>
</body>
</html>