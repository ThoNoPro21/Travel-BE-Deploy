<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComment extends Model
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
    protected $table = 'article_reviews';
    protected $primaryKey = 'article_reviews_id';
    protected $fillable = [
        'content',
        'rating',
        'user_id',
        'article_id',
    ];
}
