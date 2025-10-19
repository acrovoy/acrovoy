<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturedProductComponentCost extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $table = 'manufactured_product_component_costs';

    protected $fillable = [
        'manufactured_product_id',
        'manufactured_product_component_id',
        'supply_id',
        'component_name',
        'sku',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];

    /**
     * Изделие, к которому относится компонент.
     */
    public function manufacturedProduct()
    {
        return $this->belongsTo(ManufacturedProduct::class, 'manufactured_product_id');
    }

    /**
     * Поставка (сырьё), из которой брали материал.
     */
    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }
}
