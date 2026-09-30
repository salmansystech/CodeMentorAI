<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'title',
        'content',
        'type',
        'category',
        'url',
        'difficulty_level',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
