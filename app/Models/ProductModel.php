<?php namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['category_id', 'code', 'name', 'unit', 'stock'];
    protected $useTimestamps = false;

    // Fungsi untuk join dengan tabel kategori
    public function getProductsWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->findAll();
    }
}