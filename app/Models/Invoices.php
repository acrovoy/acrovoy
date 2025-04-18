<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'cs_invoices';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'invoice', 'payment_link'];
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}


