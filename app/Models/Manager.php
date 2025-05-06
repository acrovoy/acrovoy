<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manager extends Model
{

    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'link',
        'description',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
