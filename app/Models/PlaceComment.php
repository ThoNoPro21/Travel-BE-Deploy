<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceComment extends Model
{
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_id', 'places_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
    use HasFactory;
    protected $table = 'place_reviews';
    protected $primaryKey = 'place_reviews_id';
    protected $fillable = [
        'content',
        'user_id',
        'place_id',
    ];
}
