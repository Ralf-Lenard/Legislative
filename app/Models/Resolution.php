<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'resolutions_number',
        'title_resolutions',
        'description_resolutions',
        'date_approved_resolutions',
        'file_path_resolutions',
        'image_resolutions',
        'author_resolutions',
    ];
}
