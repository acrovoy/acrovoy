<?php

namespace App\Models;
use App\Models\Download;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['app', 'version', 'release_date', 'is_active', 'name', 'notice', 'color_of_notice', 'expiry_date', 'own_price', 'discounted_price', 'min_price', 'url', 'use'];
    public $timestamps = true;


    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
    

    
}
