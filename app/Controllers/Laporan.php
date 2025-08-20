<?php namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\IncomingItemModel;
use App\Models\OutgoingItemModel;

class Laporan extends BaseController
{   

     // Menampilkan laporan stok barang terkini.
public function stok()
{
    $productModel = new ProductModel();
    $keyword = $this->request->getGet('keyword');
    
    $query = $productModel
        ->select('products.*, categories.name as category_name')
        ->join('categories', 'categories.id = products.category_id');

    // Jika ada keyword pencarian
    if ($keyword) {
        $query->like('products.name', $keyword)
              ->orLike('products.code', $keyword);
    }
    
    $data = [
        'title'    => 'Laporan Stok Barang',
        'products' => $query->paginate(10, 'products'),
        'pager'    => $productModel->pager,
        'keyword'  => $keyword
    ];

    return view('laporan/stok', $data);
}

    
    //Menampilkan laporan barang masuk berdasarkan rentang tanggal.
    public function masuk()
    {
        $incomingItemModel = new IncomingItemModel();

        // Ambil tanggal dari query string (form GET)
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $items = [];

        if ($startDate && $endDate) {
            // Jika ada filter tanggal, ambil data dari database
            $items = $incomingItemModel->select('incoming_items.*, products.name as product_name, products.code')
                ->join('products', 'products.id = incoming_items.product_id')
                ->where('DATE(incoming_date) >=', $startDate)
                ->where('DATE(incoming_date) <=', $endDate)
                ->orderBy('incoming_date', 'ASC')
                ->findAll();
        }

        $data = [
            'title'     => 'Laporan Barang Masuk',
            'items'     => $items,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ];

        return view('laporan/masuk', $data);
    }

    
    // Menampilkan laporan barang keluar berdasarkan rentang tanggal.

    public function keluar()
    {
        $outgoingItemModel = new OutgoingItemModel();

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $items = [];

        if ($startDate && $endDate) {
            $items = $outgoingItemModel->select('outgoing_items.*, products.name as product_name, products.code')
                ->join('products', 'products.id = outgoing_items.product_id')
                ->where('DATE(outgoing_date) >=', $startDate)
                ->where('DATE(outgoing_date) <=', $endDate)
                ->orderBy('outgoing_date', 'ASC')
                ->findAll();
        }

        $data = [
            'title'     => 'Laporan Barang Keluar',
            'items'     => $items,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ];

        return view('laporan/keluar', $data);
    }
}