<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $fillable = [
        'name',
        'api_key',
        'secret_key',
    ];
}
