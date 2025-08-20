<?php namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\KategoriModel;
use App\Models\IncomingItemModel;
use App\Models\OutgoingItemModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Inisialisasi model
        $productModel = new ProductModel();
        $kategoriModel = new KategoriModel();
        $incomingItemModel = new IncomingItemModel();
        $outgoingItemModel = new OutgoingItemModel();

        // --- Data untuk Kartu Ringkasan ---
        $totalBarang = $productModel->countAllResults();
        $totalKategori = $kategoriModel->countAllResults();
        $today = date('Y-m-d');
        $transaksiMasukHariIni = $incomingItemModel->where('DATE(incoming_date)', $today)->countAllResults();
        $transaksiKeluarHariIni = $outgoingItemModel->where('DATE(outgoing_date)', $today)->countAllResults();
        $totalTransaksiHariIni = $transaksiMasukHariIni + $transaksiKeluarHariIni;

        // --- Data untuk Chart Stok ---
        $topProducts = $productModel->select('name, stock')
                                    ->orderBy('stock', 'DESC')
                                    ->limit(5)
                                    ->get()
                                    ->getResultArray();
        $chartLabels = array_column($topProducts, 'name');
        $chartData = array_column($topProducts, 'stock');

        // --- [KREASI 1] Data untuk Panel Stok Kritis ---
        $ambangBatasStok = 10; // Tentukan batas stok kritis
        $stokKritis = $productModel
            ->select('name, stock, unit')
            ->where('stock <=', $ambangBatasStok)
            ->orderBy('stock', 'ASC')
            ->findAll();

        // --- [KREASI 2] Data untuk Aktivitas Terbaru ---
        $db = \Config\Database::connect();
        $builderMasuk = $db->table('incoming_items');
        $builderMasuk->select("products.name, incoming_items.quantity, incoming_items.incoming_date as date, 'MASUK' as type")
                     ->join('products', 'products.id = incoming_items.product_id');

        $builderKeluar = $db->table('outgoing_items');
        $builderKeluar->select("products.name, outgoing_items.quantity, outgoing_items.outgoing_date as date, 'KELUAR' as type")
                      ->join('products', 'products.id = outgoing_items.product_id');
        
        $aktivitasTerbaru = $builderMasuk->union($builderKeluar)->orderBy('date', 'DESC')->limit(5)->get()->getResultArray();

        // Kirim semua data ke view
        $data = [
            'title'                 => 'Dashboard Utama',
            'totalBarang'           => $totalBarang,
            'totalKategori'         => $totalKategori,
            'totalTransaksiHariIni' => $totalTransaksiHariIni,
            'chartLabels'           => json_encode($chartLabels),
            'chartData'             => json_encode($chartData),
            'stokKritis'            => $stokKritis,
            'aktivitasTerbaru'      => $aktivitasTerbaru,
        ];
        
        return view('dashboard', $data);
    }
}