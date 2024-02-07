<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'description',
        'price',
        'location',
        'image_url_1',
        'image_url_2',
        'image_url_3',
        'image_url_4'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bookings():HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // public function scopeFilter(Builder | QueryBuilder $query, array $filters): Builder | QueryBuilder
    // {
        
    // }
    

}
