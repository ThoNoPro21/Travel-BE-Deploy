<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'products_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'carts_id';
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
    ];
}
