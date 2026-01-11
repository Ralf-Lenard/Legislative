<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolutionDownloadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resolution_id',
        'purpose',
        'status',
        'is_downloaded',
        'rejection_reason'
    ];

    // Relationship: Each request belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Each request belongs to an ordinance
    public function resolution()
    {
        return $this->belongsTo(Resolution::class);
    }
}
