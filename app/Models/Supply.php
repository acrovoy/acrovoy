<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'warehouse_id',
        'date_received',
        'document_number',
        'supplier_id',
        'supplier_name',
        'sku',
        'name',
        'category_id',
        'unit',
        'quantity',
        'price_per_unit',
        'batch_number',
        'quantity_used',
        'quantity_remaining',
        'notes',
    ];

    protected $casts = [
    'date_received' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function category()
    {
        return $this->belongsTo(SupplyCategory::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }


    public function components()
{
    return $this->hasMany(ManufacturedProductComponent::class, 'supply_id');
}

public static function getTotalRemainingBySku(string $sku, int $warehouseId)
{
    return self::where('sku', $sku)
        ->where('warehouse_id', $warehouseId)
        ->sum('quantity_remaining');
}

}
