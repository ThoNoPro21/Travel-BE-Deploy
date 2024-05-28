<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleFavourite extends Model
{
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'articles_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }
    use HasFactory;
    protected $table = 'article_favorites';
    protected $primaryKey = 'article_favorites_id';
    protected $fillable = [
        'user_id',
        'article_id',
    ];
}
