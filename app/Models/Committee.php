<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'focus',
    ];

    /**
     * The officials that belong to the committee.
     */
    public function officials()
    {
        return $this->belongsToMany(Official::class, 'committee_official')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
