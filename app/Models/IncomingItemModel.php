<?php namespace App\Models;

use CodeIgniter\Model;

class IncomingItemModel extends Model
{
    protected $table = 'incoming_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['purchase_id', 'product_id', 'quantity', 'incoming_date'];
    protected $useTimestamps = false;
}