<?php namespace App\Controllers;

use App\Models\IncomingItemModel;
use App\Models\ProductModel;
use App\Models\PurchaseModel;
use App\Models\PurchaseDetailModel;

class BarangMasukController extends BaseController
{
    public function index()
{
    $incomingItemModel = new IncomingItemModel();
    $keyword = $this->request->getGet('keyword'); 

    $query = $incomingItemModel
        ->select('incoming_items.*, products.name as product_name, products.code')
        ->join('products', 'products.id = incoming_items.product_id');

    if ($keyword) {
        $query->like('products.name', $keyword)
              ->orLike('products.code', $keyword);
    }

    $data = [
        'title' => 'Log Barang Masuk',
        'items' => $query->orderBy('incoming_date', 'DESC')
                       ->paginate(10, 'incoming_items'),
        'pager' => $incomingItemModel->pager,
        'keyword' => $keyword,
    ];
    
    return view('barang_masuk/index', $data);
}

    public function process()
    {
        $purchaseId = $this->request->getVar('purchase_id');
        if (!$purchaseId) {
            return redirect()->back()->with('error', 'ID Pembelian tidak valid.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $purchaseModel = new PurchaseModel();
            $purchaseDetailModel = new PurchaseDetailModel();
            $incomingItemModel = new IncomingItemModel();
            $productModel = new ProductModel();

            // 1. Ambil semua detail dari pembelian ini
            $details = $purchaseDetailModel->where('purchase_id', $purchaseId)->findAll();

            if (empty($details)) {
                throw new \Exception('Tidak ada detail produk untuk pembelian ini.');
            }

            // 2. Loop dan proses setiap detail
            foreach ($details as $item) {
                // Catat ke log barang masuk
                $incomingItemModel->save([
                    'product_id'    => $item['product_id'],
                    'purchase_id'   => $purchaseId, // Simpan referensi
                    'quantity'      => $item['quantity'],
                    'incoming_date' => date('Y-m-d H:i:s')
                ]);

                // Update stok produk
                $productModel->where('id', $item['product_id'])->increment('stock', $item['quantity']);
            }

            // 3. Update status pembelian menjadi "Completed"
            $purchaseModel->update($purchaseId, ['status' => 'Completed']);

            $db->transComplete();
            
            session()->setFlashdata('success', 'Pembelian berhasil diproses dan stok telah diperbarui.');
            return redirect()->to('purchase/' . $purchaseId);

        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memproses barang masuk: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}