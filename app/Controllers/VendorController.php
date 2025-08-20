<?php namespace App\Controllers;

use App\Models\VendorModel;

class VendorController extends BaseController
{
    protected $vendorModel;

    public function __construct()
    {
        $this->vendorModel = new VendorModel();
    }

    public function index()
{
    // Ambil keyword pencarian dari URL
    $keyword = $this->request->getGet('keyword');

    $query = $this->vendorModel;

    // Jika ada keyword, tambahkan filter pencarian
    if ($keyword) {
        $query->like('name', $keyword)
              ->orLike('address', $keyword)
              ->orLike('phone', $keyword);
    }

    $data = [
        'title'   => 'Daftar Vendor',
        'vendors' => $query->paginate(10, 'vendors'),
        'pager'   => $this->vendorModel->pager,
        'keyword' => $keyword, // Kirim keyword kembali ke view
    ];
    return view('vendor/index', $data);
}

    public function new()
    {
        $data = [
            'title'      => 'Tambah Vendor Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('vendor/create', $data);
    }

    public function create()
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'phone' => 'permit_empty|min_length[5]|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/vendor/new')->withInput()->with('validation', $this->validator);
        }

        $this->vendorModel->save([
            'name'    => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'phone'   => $this->request->getVar('phone'),
        ]);

        session()->setFlashdata('success', 'Data vendor berhasil ditambahkan.');
        return redirect()->to('/vendor');
    }

    public function edit($id = null)
    {
        $vendor = $this->vendorModel->find($id);
        if (empty($vendor)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Vendor tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Vendor',
            'vendor'     => $vendor,
            'validation' => \Config\Services::validation()
        ];
        return view('vendor/edit', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'phone' => 'permit_empty|min_length[5]|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/vendor/edit/' . $id)->withInput()->with('validation', $this->validator);
        }

        $this->vendorModel->update($id, [
            'name'    => $this->request->getVar('name'),
            'address' => $this->request->getVar('address'),
            'phone'   => $this->request->getVar('phone'),
        ]);

        session()->setFlashdata('success', 'Data vendor berhasil diubah.');
        return redirect()->to('/vendor');
    }

    public function delete($id = null)
    {
        $this->vendorModel->delete($id);
        session()->setFlashdata('success', 'Data vendor berhasil dihapus.');
        return redirect()->to('/vendor');
    }
}