<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OctoSetting extends Model
{
    protected $table = 'octo_settings';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'theme',
        'signal',
        'gmt_id',
    ];

    public function gmt()
    {
        return $this->belongsTo(Gmt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


   
}
