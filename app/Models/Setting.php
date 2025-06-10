<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Setting extends Model
{
     // Указываем имя таблицы, если оно не совпадает с именем модели во множественном числе
     protected $table = 'user_settings';

     // Указываем, что модель не использует временные метки (created_at, updated_at)
     public $timestamps = false;
 
     // Определяем, какие столбцы можно массово заполнять
     protected $fillable = [
         'user_id',
         'display_length',
         'font_size',
         'lv1_volume',
         'lv2_volume',
         'lv3_volume',
         'scan_distance',
         'additional_spot',
         'additional_futures',
         'blacklisted_spot',
         'blacklisted_futures',
         'market',
         'exchange',
         'proxy',
     ];
 
     // Определяем связь с моделью User
     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }
}
