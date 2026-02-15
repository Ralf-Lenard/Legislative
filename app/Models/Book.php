<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Allow mass assignment
    protected $fillable = [
        'title',
        'author',
        'category',
        'published_year',
        'image', // ← added for book cover
        'description'
    ];
}
