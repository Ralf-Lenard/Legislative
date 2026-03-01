<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    protected $fillable = [
        'type',
        'full_name',
        'barangay',
        'school',
    ];
}