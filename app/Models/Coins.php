<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coins extends Model
{

    
    protected $table = 'coins';
    protected $primaryKey = 'id';
    protected $fillable = ['symbol', 'contract' , 'check_volume', 'check_volume_f'];
    public $timestamps = false;



}
