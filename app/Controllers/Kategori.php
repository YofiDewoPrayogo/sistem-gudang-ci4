<?php namespace App\Controllers;

use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

public function index()
    {
        // Ambil keyword pencarian dari URL
        $keyword = $this->request->getGet('keyword');
        
        // Ambil parameter untuk sortir, dengan nilai default
        $sort = $this->request->getGet('sort') ?? 'id';
        $order = $this->request->getGet('order') ?? 'DESC';

        $query = $this->kategoriModel;

        // Jika ada keyword, tambahkan kondisi 'like'
        if ($keyword) {
            $query->like('name', $keyword);
        }

        // Terapkan sortir ke query database
        $query->orderBy($sort, $order);

        $data = [
            'title'    => 'Daftar Kategori',
            'kategori' => $query->paginate(10, 'categories'),
            'pager'    => $this->kategoriModel->pager,
            'keyword'  => $keyword,
            'sort'     => $sort,  // Kirim informasi sortir ke view
            'order'    => $order, // Kirim informasi urutan ke view
        ];
        
        return view('kategori/index', $data);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'validation' => \Config\Services::validation()
        ];
        return view('kategori/create', $data);
    }

    public function save()
    {
        if (!$this->validate(['name' => 'required|min_length[3]|is_unique[categories.name]'])) {
            return redirect()->to('/kategori/create')->withInput();
        }

        $this->kategoriModel->save(['name' => $this->request->getVar('name')]);
        session()->setFlashdata('success', 'Data kategori berhasil ditambahkan.');
        return redirect()->to('/kategori');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kategori',
            'kategori' => $this->kategoriModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('kategori/edit', $data);
    }

    public function update($id)
    {
        $oldCategory = $this->kategoriModel->find($id);
        $rule = ($oldCategory['name'] == $this->request->getVar('name')) ? 'required' : 'required|is_unique[categories.name]';

        if (!$this->validate(['name' => $rule])) {
            return redirect()->to('/kategori/edit/' . $id)->withInput();
        }

        $this->kategoriModel->update($id, ['name' => $this->request->getVar('name')]);
        session()->setFlashdata('success', 'Data kategori berhasil diubah.');
        return redirect()->to('/kategori');
    }

    public function delete($id)
    {
        $this->kategoriModel->delete($id);
        session()->setFlashdata('success', 'Data kategori berhasil dihapus.');
        return redirect()->to('/kategori');
    }
}