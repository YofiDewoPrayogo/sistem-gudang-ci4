<?php namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'address', 'phone'];
}