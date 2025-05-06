<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerProduct extends Model
{
    protected $table = 'manager_products';

    protected $fillable = [
        'manager_id',
        'product_id',
        'price',
    ];

    public $timestamps = false;

    // Связь с моделью Manager
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    // Связь с моделью Product
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
