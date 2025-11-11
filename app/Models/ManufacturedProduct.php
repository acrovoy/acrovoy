<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturedProduct extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'warehouse_id',
        'produced_quantity',
        'unit',
        'notes',
        'serial_number',
        'manufactured_at',
        'cost', 
        'status'
    ];

  

    // Связь со складом
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}


public function components()
{
    return $this->hasMany(ManufacturedProductComponent::class);
}


public function getCostAttribute()
{
    return $this->components->sum(function($component){
        $price = $component->supply?->price_per_unit ?? 0; // теперь price_per_unit
        return $component->quantity * $price;
    });
}


public function componentCosts()
{
    return $this->hasMany(\App\Models\ManufacturedProductComponentCost::class, 'manufactured_product_id');
}


}
