<?php namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $allowedFields = ['vendor_id', 'purchase_date', 'buyer_name', 'total_amount', 'status'];
}