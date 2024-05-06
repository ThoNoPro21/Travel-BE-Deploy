<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory;
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'locations_id');
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'place_id', 'places_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(PlaceComment::class, 'place_id', 'places_id');
    }
    protected $table = 'places';
    protected $primaryKey = 'places_id';
    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'images',
        'location_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = ['images' => 'array',];



    // protected $attributes = [
    //     'options' => '[]',
    //     'delayed' => false,
    // ];
    //     const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';
}
