<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'categories_id');
    }
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'categories_id';
    protected $fillable = [
        'name',
    ];
}
