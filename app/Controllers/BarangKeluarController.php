<?php namespace App\Controllers;

use App\Models\OutgoingItemModel;
use App\Models\ProductModel;

class BarangKeluarController extends BaseController
{
    protected $outgoingItemModel;
    protected $productModel;

    public function __construct()
    {
        $this->outgoingItemModel = new OutgoingItemModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
    $keyword = $this->request->getGet('keyword');
    
    $query = $this->outgoingItemModel
        ->select('outgoing_items.*, products.name as product_name, products.code')
        ->join('products', 'products.id = outgoing_items.product_id');

    if ($keyword) {
        $query->like('products.name', $keyword)
              ->orLike('products.code', $keyword);
    }

    $data = [
        'title' => 'Daftar Barang Keluar',
        'items' => $query->orderBy('outgoing_date', 'DESC')->paginate(10, 'outgoing_items'),
        'pager' => $this->outgoingItemModel->pager,
        'keyword' => $keyword,
    ];
    return view('barang_keluar/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Transaksi Barang Keluar',
            'products'   => $this->productModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('barang_keluar/create', $data);
    }

    public function save()
    {
        $rules = [
            'product_id' => 'required|numeric',
            'quantity'   => 'required|numeric|greater_than[0]',
            'notes'      => 'permit_empty|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/barang-keluar/create')->withInput();
        }

        $productId = $this->request->getVar('product_id');
        $quantity = (int)$this->request->getVar('quantity');

        $product = $this->productModel->find($productId);
        if (!$product || $product['stock'] < $quantity) {
            session()->setFlashdata('error', 'Gagal! Stok barang tidak mencukupi.');
            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->outgoingItemModel->save([
            'product_id'    => $productId,
            'quantity'      => $quantity,
            'outgoing_date' => date('Y-m-d H:i:s'),
            'notes'         => $this->request->getVar('notes')
        ]);

        $this->productModel->where('id', $productId)->decrement('stock', $quantity);
        $db->transComplete();
        
        if ($db->transStatus() === FALSE) {
            session()->setFlashdata('error', 'Gagal mencatat data barang keluar.');
        } else {
            session()->setFlashdata('success', 'Data barang keluar berhasil dicatat dan stok telah diupdate.');
        }

        return redirect()->to('/barang-keluar');
    }
}