<?php namespace App\Controllers;

use App\Models\barangModel;

class barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new barangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar barang',
            'barang' => $this->barangModel->findAll()
        ];
        return view('barang/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah barang'];
        return view('barang/create', $data);
    }

    public function save()
    {
        $this->barangModel->save([
            'name' => $this->request->getVar('name')
        ]);
        session()->setFlashdata('success', 'Data barang berhasil ditambahkan.');
        return redirect()->to('/barang');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit barang',
            'barang' => $this->barangModel->find($id)
        ];
        return view('barang/edit', $data);
    }

    public function update($id)
    {
        $this->barangModel->update($id, [
            'name' => $this->request->getVar('name')
        ]);
        session()->setFlashdata('success', 'Data barang berhasil diubah.');
        return redirect()->to('/barang');
    }

    public function delete($id)
    {
        $this->barangModel->delete($id);
        session()->setFlashdata('success', 'Data barang berhasil dihapus.');
        return redirect()->to('/barang');
    }
}