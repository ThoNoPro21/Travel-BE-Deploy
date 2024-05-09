<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;
    protected $table = 'carousels';
    protected $primaryKey = 'carousels_id';
    protected $fillable = [
        'image',
        'publicId',
        'status',
    ];
    protected $attributes = [
        'status' => 0,
    ];
}
