<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinanceDownloadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ordinance_id',
        'purpose',
        'valid_id_type',
        'valid_id_path',
        'status',
        'rejection_reason',
        'is_downloaded',
    ];

    // ✅ Add this line to append custom attributes to JSON/Inertia response
    protected $appends = ['valid_id_url'];

    // ✅ This creates the URL for the browser
    public function getValidIdUrlAttribute()
    {
        return $this->valid_id_path ? asset('storage/' . $this->valid_id_path) : null;
    }

    // Relations
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ordinance() {
        return $this->belongsTo(Ordinance::class);
    }
}
