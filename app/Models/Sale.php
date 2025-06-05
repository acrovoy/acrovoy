<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{

    public $timestamps = true;
    protected $table = 'sales';
    protected $fillable = [
        'product_id',
        'buyer_id',
        'manager_id',
        'invoice_id',
        'price',
        'own_price',
        'site_price',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function manager()
{
    return $this->belongsTo(Manager::class, 'manager_id');
}

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    public function invoiceDetails()
{
    return $this->hasOne(Invoices::class, 'product_id', 'product_id')
                ->whereColumn('user_id', 'buyer_id');
}



public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}




}


