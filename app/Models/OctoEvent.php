<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OctoEvent extends Model
{
    protected $table = 'octo_events';

    public $timestamps = true;

    protected $casts = [
    'datetime' => 'datetime',
];

    protected $fillable = [
        'user_id',
        'title',
        'datetime',
        'flag',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
