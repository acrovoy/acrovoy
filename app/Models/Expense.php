<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Expense extends Model
{

    use HasFactory;


    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
    'date',
    'document_number',
    'supplier',
    'supplier_id',
    'description',
    'category',
    'amount',
    'payment_method',
    'account_article',
    'warehouse',
    'comment'
];


public function supplierRelation()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }





}
