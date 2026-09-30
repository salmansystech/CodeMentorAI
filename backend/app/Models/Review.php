<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'findings',
        'overall_score',
        'bugs_count',
        'style_issues_count',
        'performance_issues_count',
        'security_issues_count',
        'created_at',
    ];

    protected $casts = [
        'findings' => 'json',
        'created_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
