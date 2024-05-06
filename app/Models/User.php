<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id', 'users_id');
    }
    public function ArticleComments(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'user_id', 'users_id');
    }
    public function PlaceComments(): HasMany
    {
        return $this->hasMany(PlaceComment::class, 'user_id', 'users_id');
    }
    public function oders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'users_id');
    }
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'users_id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $attributes = [
        'role' => 3,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
