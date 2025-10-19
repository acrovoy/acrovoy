<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = ['name'];
}