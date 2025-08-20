<?php namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\KategoriModel;

class BarangController extends BaseController
{
    protected $productModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');

        $query = $this->productModel
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id');

        if ($keyword) {
            $query->like('products.name', $keyword)
                  ->orLike('products.code', $keyword);
        }

        $data = [
            'title'    => 'Daftar Barang',
            'products' => $query->paginate(10, 'products'),
            'pager'    => $this->productModel->pager,
            'keyword'  => $keyword,
        ];

        return view('barang/index', $data);
    }
    
    public function new()
    {
        $data = [
            'title'      => 'Tambah Barang Baru',
            'kategori'   => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('barang/create', $data);
    }

    public function create()
    {
        $rules = [
            'name'        => 'required|min_length[3]',
            'code'        => 'required|is_unique[products.code]',
            'category_id' => 'required|numeric',
            'unit'        => 'required',
            'stock'       => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/barang/create')->withInput()->with('validation', $this->validator);
        }

        $this->productModel->save([
            'name'        => $this->request->getVar('name'),
            'code'        => $this->request->getVar('code'),
            'category_id' => $this->request->getVar('category_id'),
            'unit'        => $this->request->getVar('unit'),
            'stock'       => $this->request->getVar('stock')
        ]);

        session()->setFlashdata('success', 'Data barang berhasil ditambahkan.');
        return redirect()->to('/barang');
    }

    public function edit($id = null)
    {
        $product = $this->productModel->find($id);

        if (empty($product)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang dengan ID ' . $id . ' tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Barang',
            'product'    => $product,
            'kategori'   => $this->kategoriModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        
        return view('barang/edit', $data);
    }

    public function update($id = null)
    {
        $oldProduct = $this->productModel->find($id);
        $codeRule = ($oldProduct['code'] == $this->request->getVar('code')) 
                    ? 'required' 
                    : "required|is_unique[products.code]";

        $rules = [
            'name'        => 'required|min_length[3]',
            'code'        => $codeRule,
            'category_id' => 'required|numeric',
            'unit'        => 'required',
            'stock'       => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/barang/edit/' . $id)->withInput()->with('validation', $this->validator);
        }

        $this->productModel->update($id, [
            'name'        => $this->request->getVar('name'),
            'code'        => $this->request->getVar('code'),
            'category_id' => $this->request->getVar('category_id'),
            'unit'        => $this->request->getVar('unit'),
            'stock'       => $this->request->getVar('stock')
        ]);
        
        session()->setFlashdata('success', 'Data barang berhasil diubah.');
        return redirect()->to('/barang');
    }

    public function delete($id = null)
    {
        $product = $this->productModel->find($id);
        if ($product) {
            $this->productModel->delete($id);
            session()->setFlashdata('success', 'Data barang berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data barang tidak ditemukan.');
        }
        
        return redirect()->to('/barang');
    }
}