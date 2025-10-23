<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RawMaterial;

class ProductModelComponent extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'product_model_id', // <--- добавляем сюда
        'raw_material_id',
        'quantity',
    ];

    // Связь с моделью продукта
    public function productModel()
    {
        return $this->belongsTo(ProductModel::class);
    }

    // Связь с поставкой
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
