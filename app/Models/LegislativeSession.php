<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegislativeSession extends Model
{
    use HasFactory;

    // Table name (optional if following Laravel conventions)
    protected $table = 'legislative_sessions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'session_number',
        'session_title',
        'date_of_session',
        'session_type',
        'summary',
        'images',
        'videos',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_session' => 'date',
        'images' => 'array', // Automatically decode JSON to array
        'videos' => 'array',
    ];
}
