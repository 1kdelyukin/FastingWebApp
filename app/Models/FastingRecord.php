<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastingRecord extends Model
{
    use HasFactory;

    protected $table = 'fasting_records';
    
    // Optionally specify which attributes can be mass assignable
    protected $fillable = [
        'user_id',
        'record_date',
        'total_fasting_minutes',
    ];
    
}
