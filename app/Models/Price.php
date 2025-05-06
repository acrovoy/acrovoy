<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{

    public $timestamps = true;
    protected $fillable = [
        'product_id',
        'manager_id',
        'price',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
