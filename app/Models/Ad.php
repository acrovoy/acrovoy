<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    // Указываем таблицу, если имя таблицы не соответствует соглашению
    protected $table = 'ads';

    // Указываем, какие поля могут быть массово присвоены
    protected $fillable = [
        'image_path',
        'link',
        'date_from',
        'date_to',
        'advertiser_name',
        'advertiser_contact',
    ];

    // Автоматически добавляем временные метки created_at и updated_at
    public $timestamps = true;
}
