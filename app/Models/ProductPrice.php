<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'sku',
        'price_type_id',
        'price',
    ];

    // Связь с моделью товара по SKU
    public function productModel()
{
    return $this->belongsTo(ProductModel::class, 'sku', 'sku');
}

public function type()
{
    return $this->belongsTo(PriceType::class, 'price_type_id');
}


public function manufacturedProduct()
{
    return $this->hasOne(ManufacturedProduct::class, 'sku', 'sku');
}

}
