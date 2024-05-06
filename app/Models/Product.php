<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'categories_id');
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'locations_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(ProductComment::class, 'product_id', 'products_id');
    }

    protected $table = 'products';
    protected $primaryKey = 'products_id';
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'location_id',
        'category_id',
        'images'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'images' => 'json',
    ];
    protected $attributes = [
        'price_sale' => 0,
    ];



    // protected $attributes = [
    //     'options' => '[]',
    //     'delayed' => false,
    // ];
    //     const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';
}
