<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'main_committee',
        'image',
        'bio',
    ];

    /**
     * The committees that belong to the official.
     */
    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_official')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
