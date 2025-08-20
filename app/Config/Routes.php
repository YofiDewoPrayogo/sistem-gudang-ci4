<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute untuk proses Autentikasi (bisa diakses publik)
$routes->get('/login', 'AuthController::index');
$routes->post('/auth/process', 'AuthController::process');
$routes->get('/logout', 'AuthController::logout');


// Grup rute yang memerlukan login (dijaga oleh filter 'auth')
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    
    // Rute untuk Kategori
    $routes->get('kategori', 'Kategori::index');
    $routes->get('kategori/create', 'Kategori::create');
    $routes->post('kategori/save', 'Kategori::save');
    $routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
    $routes->post('kategori/update/(:num)', 'Kategori::update/$1');
    $routes->delete('kategori/delete/(:num)', 'Kategori::delete/$1');

    // Rute untuk Barang
    $routes->resource('barang', ['controller' => 'BarangController']);
    
    // Rute untuk Transaksi
    $routes->get('barang-masuk', 'BarangMasukController::index');
    $routes->post('barang-masuk/process', 'BarangMasukController::process');
    $routes->get('barang-masuk/create', 'BarangMasukController::create');
    $routes->post('barang-masuk/save', 'BarangMasukController::save');
    $routes->post('barang-masuk/process', 'BarangMasukController::process');
    
    $routes->get('barang-keluar', 'BarangKeluarController::index');
    $routes->get('barang-keluar/create', 'BarangKeluarController::create');
    $routes->post('barang-keluar/save', 'BarangKeluarController::save');

    // Rute untuk Laporan
    $routes->get('laporan/stok', 'Laporan::stok');
    $routes->get('laporan/masuk', 'Laporan::masuk');
    $routes->get('laporan/keluar', 'Laporan::keluar');
    
    // Rute untuk Profile
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');

    // Rute untuk Vendor
    $routes->resource('vendor', ['controller' => 'VendorController']);

    // Rute untuk purchase
    $routes->resource('purchase', ['controller' => 'PurchaseController']);
});