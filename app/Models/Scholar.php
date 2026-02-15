<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'scholars';

    // Mass assignable fields
    protected $fillable = [
        'full_name',
        'image',
        'course',
        'year_level',
    ];
}
