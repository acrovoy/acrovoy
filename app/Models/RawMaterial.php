<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
        'name',
        'unit',
        'code',
        'description',
        'photo',
    ];
}
