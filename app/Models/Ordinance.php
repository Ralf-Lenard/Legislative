<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordinance extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordinance_number',
        'title_ordinances',
        'description_ordinances',
        'date_approved_ordinances',
        'file_path_ordinances',
        'image_ordinances',
        'author_ordinances',
    ];
}
