<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'points_required',
        'condition',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
