<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'orders_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'products_id');
    }
    use HasFactory;
    protected $table = 'order_details';
    protected $primaryKey = 'order_details_id';
    protected $fillable = [
        'quantity',
        'total_amount',
        'order_id',
        'product_id',
    ];
}
