<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gmt extends Model
{
    protected $table = 'gmt';

    public $timestamps = false;

    protected $casts = [
    'offset' => 'integer',
    ];

    protected $fillable = [
        'label',
        'offset',
    ];

    public function settings()
    {
        return $this->hasMany(OctoSetting::class, 'gmt_id');
    }
}
