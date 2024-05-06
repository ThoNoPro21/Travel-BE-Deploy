<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'topic_id', 'topics_id');
    }
    use HasFactory;
    protected $table = 'topics';
    protected $primaryKey = 'topics_id';
    protected $fillable = [
        'name',
    ];
}