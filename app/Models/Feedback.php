<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'category',
        'message',
    ];
    /**
     * Relationship: Feedback belongs to a User (optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}