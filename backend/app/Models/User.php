<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'total_points',
        'current_streak',
        'best_streak',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges');
    }

    public function addPoints($points)
    {
        $this->total_points += $points;
        $this->updateLevel();
        $this->save();
    }

    public function addBadge($badgeId)
    {
        if (!$this->badges()->where('badge_id', $badgeId)->exists()) {
            $this->badges()->attach($badgeId);
        }
    }

    private function updateLevel()
    {
        $points = $this->total_points;
        if ($points >= 10000) {
            $this->level = 50;
        } elseif ($points >= 5000) {
            $this->level = 40;
        } elseif ($points >= 2500) {
            $this->level = 30;
        } elseif ($points >= 1000) {
            $this->level = 20;
        } elseif ($points >= 500) {
            $this->level = 10;
        } else {
            $this->level = 1;
        }
    }
}
