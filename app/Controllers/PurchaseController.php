<?php namespace App\Controllers;

use App\Models\PurchaseModel;
use App\Models\PurchaseDetailModel;
use App\Models\VendorModel;
use App\Models\ProductModel;

class PurchaseController extends BaseController
{
    public function index()
    {
        $purchaseModel = new PurchaseModel();
        $data = [
            'title'     => 'Daftar Pembelian',
            'purchases' => $purchaseModel
                ->select('purchases.*, vendors.name as vendor_name')
                ->join('vendors', 'vendors.id = purchases.vendor_id')
                ->orderBy('purchase_date', 'DESC')
                ->paginate(10, 'purchases'),
            'pager' => $purchaseModel->pager,
        ];
        return view('purchase/index', $data);
    }

    public function new()
    {
        $vendorModel = new VendorModel();
        $productModel = new ProductModel();
        $data = [
            'title'      => 'Buat Transaksi Pembelian Baru',
            'vendors'    => $vendorModel->findAll(),
            'products'   => $productModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('purchase/create', $data);
    }

    public function create()
    {
        // Validasi data utama
        $rules = [
            'vendor_id'     => 'required|numeric',
            'purchase_date' => 'required|valid_date',
            'buyer_name'    => 'required|min_length[3]',
            'products'      => 'required' // Validasi agar minimal ada 1 produk
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/purchase/new')->withInput()->with('validation', $this->validator);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $purchaseModel = new PurchaseModel();
            $purchaseDetailModel = new PurchaseDetailModel();

            // 1. Simpan data ke tabel purchases
            $purchaseModel->save([
                'vendor_id'     => $this->request->getVar('vendor_id'),
                'purchase_date' => $this->request->getVar('purchase_date'),
                'buyer_name'    => $this->request->getVar('buyer_name'),
                'status'        => 'Pending', // Status awal
            ]);
            $purchaseId = $purchaseModel->getInsertID();
            
            // 2. Simpan detail produk ke tabel purchase_details
            $products = $this->request->getVar('products');
            $totalAmount = 0;
            foreach ($products as $p) {
                $purchaseDetailModel->save([
                    'purchase_id' => $purchaseId,
                    'product_id'  => $p['product_id'],
                    'quantity'    => $p['quantity'],
                    'price'       => $p['price'],
                ]);
                $totalAmount += $p['quantity'] * $p['price'];
            }

            // 3. Update total_amount di tabel purchases
            $purchaseModel->update($purchaseId, ['total_amount' => $totalAmount]);

            $db->transComplete();

            session()->setFlashdata('success', 'Transaksi pembelian berhasil dibuat.');
            return redirect()->to('/purchase');

        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal membuat transaksi pembelian: ' . $e->getMessage());
            return redirect()->to('/purchase/new')->withInput();
        }
    }

    public function show($id = null)
    {
        $purchaseModel = new PurchaseModel();
        $purchaseDetailModel = new PurchaseDetailModel();
        
        $purchase = $purchaseModel
            ->select('purchases.*, vendors.name as vendor_name, vendors.address, vendors.phone')
            ->join('vendors', 'vendors.id = purchases.vendor_id')
            ->find($id);

        if (empty($purchase)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pembelian tidak ditemukan.');
        }

        $details = $purchaseDetailModel
            ->select('purchase_details.*, products.name as product_name, products.code')
            ->join('products', 'products.id = purchase_details.product_id')
            ->where('purchase_id', $id)
            ->findAll();

        $data = [
            'title'    => 'Detail Pembelian',
            'purchase' => $purchase,
            'details'  => $details
        ];
        return view('purchase/show', $data);
    }
}