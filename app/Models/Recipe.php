<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    protected $fillable = [
        'name',
        'image',
        'ingredients',
        'directions',
        'page_slug',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'directions' => 'array',
    ];
}
