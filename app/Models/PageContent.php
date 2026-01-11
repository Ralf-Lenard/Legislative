<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'welcome_image',
        'about_us_image',
        'vice_mayor_image',
        'vice_mayor_message',
        'about_us',
        'mission',
        'vision',
        'gallery_images',
    ];

    protected $casts = [
        'gallery_images' => 'array',
    ];
}
