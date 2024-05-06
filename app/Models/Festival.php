<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Festival extends Model
{
    use HasFactory;
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'locations_id');
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'festival_id', 'festivals_id');
    }
    protected $table = 'festivals';
    protected $primaryKey = 'festivals_id';
    protected $fillable = [
        'name',
        'description',
        'address',
        'start_date',
        'end_date',
        'price',
        'images',
        'location_id'
    ];
    protected $attributes = [
        'status' => 0,
    ];
}