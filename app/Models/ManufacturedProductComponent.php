<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturedProductComponent extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'manufactured_product_id',
        'supply_id',
        'quantity'
    ];

    // Связь с изделием
    public function manufacturedProduct()
    {
        return $this->belongsTo(ManufacturedProduct::class);
    }

    // Связь с компонентом (поставкой)
  public function supply()
{
    return $this->belongsTo(Supply::class, 'supply_id');
}
}
