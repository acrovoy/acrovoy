<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    use HasFactory;

    
    protected $connection = 'mysql_rattan';
    protected $fillable = [
        'date',
        'document_number',
        'client_id',
        'warehouse_id',
        'payment_method_id',
        'payment_term_id',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'datetime', // или 'datetime' если ты поменял тип поля
    ];

    public function items()
    {
        return $this->hasMany(ProductSaleItem::class, 'product_sale_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }
}
