<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['app', 'version', 'release_date', 'is_active', 'name', 'notice', 'color_of_notice', 'expiry_date', 'own_price', 'discounted_price', 'min_price'];
    public $timestamps = true;

    

    
}
