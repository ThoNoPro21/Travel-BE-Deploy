<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'location_id', 'locations_id');
    }
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'location_id', 'locations_id');
    }
    public function festivals(): HasMany
    {
        return $this->hasMany(Festival::class, 'location_id', 'locations_id');
    }
    use HasFactory;
    protected $table = 'locations';
    protected $primaryKey = 'locations_id';
    protected $fillable = [
        'name'
    ];
}