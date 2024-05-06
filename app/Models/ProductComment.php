<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductComment extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'products_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
    use HasFactory;
    protected $table = 'product_reviews';
    protected $primaryKey = 'product_reviews_id';
    protected $fillable = [
        'content',
        'user_id',
        'rating',
        'product_id',
    ];
}
