<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Versions extends Model
{
    protected $table = 'versions';
    protected $primaryKey = 'id';
    protected $fillable = ['app', 'version', 'release_date', 'is_active', 'description', 'notice', 'color_of_notice', 'expiry_date', 'personal_notice'];
    public $timestamps = true;

    

    
}
