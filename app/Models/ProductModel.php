<?php
namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = ['product_name', 'description', 'price'];

    // Enable automatic timestamps
    protected $useTimestamps = true;

    // Define the column names in your DB
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
