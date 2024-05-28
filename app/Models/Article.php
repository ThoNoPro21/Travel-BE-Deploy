<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'locations_id');
    }
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_id', 'places_id');
    }
    public function festival(): BelongsTo
    {
        return $this->belongsTo(Festival::class, 'festival_id', 'festivals_id');
    }
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'topics_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id', 'articles_id');
    }
    public function favourites(): HasMany
    {
        return $this->hasMany(ArticleFavourite::class, 'article_id', 'articles_id');
    }
    use HasFactory;
    protected $table = 'articles';
    protected $primaryKey = 'articles_id';
    protected $fillable = [
        'title',
        'topic_id',
        'content',
        'status',
        'images',
        'place_id',
        'festival_id',
        'user_id',
        'location_id'
    ];
}
