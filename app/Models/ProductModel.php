<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'name', 'sku', 'description', 'photo',
    ];

    // Связь с компонентами
    public function components()
    {
        return $this->hasMany(ProductModelComponent::class);
    }

    public function prices()
{
    return $this->hasMany(ProductPrice::class, 'sku', 'sku');
}
}
