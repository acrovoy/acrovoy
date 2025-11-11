<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSaleItem extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan';
    protected $fillable = [
        'product_sale_id',
        'supply_id',
        'sku',
        'name',
        'quantity',
        'price',
        'total',
    ];

    public function sale()
    {
        return $this->belongsTo(ProductSale::class, 'product_sale_id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
