<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = ['code', 'name'];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
