<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'orders_id');
    }
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_id', 'users_id');
    }
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'orders_id';
    protected $fillable = [
        'address',
        'phone_number',
        'total_amount',
        'note',
        'status',
        'user_id'
    ];
}
