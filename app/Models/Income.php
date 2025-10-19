<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    
    use HasFactory;
    
    protected $connection = 'mysql_rattan'; // <-- подключение к базе rattan
    protected $fillable = [
    'date',
    'document_number',
    'client',
    'client_id',
    'description',
    'category',
    'amount',
    'payment_method',
    'account_article',
    'warehouse',
    'comment'
    ];

    public function clientRelation()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    

    protected $casts = [
    'date' => 'datetime',
];


}
