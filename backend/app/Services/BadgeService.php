<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;

class BadgeService
{
    protected $badges = [
        'starter' => [
            'name' => 'Getting Started',
            'description' => 'Complete your first code submission',
            'icon' => 'star',
            'points_required' => 0,
            'condition' => 'first_submission',
        ],
        'bug_hunter' => [
            'name' => 'Bug Hunter',
            'description' => 'Find and fix 10 bugs',
            'icon' => 'bug',
            'points_required' => 100,
            'condition' => 'bugs_found:10',
        ],
        'code_master' => [
            'name' => 'Code Master',
            'description' => 'Get 50 perfect scores',
            'icon' => 'trophy',
            'points_required' => 500,
            'condition' => 'perfect_scores:50',
        ],
        'on_fire' => [
            'name' => 'On Fire',
            'description' => 'Maintain a 7-day submission streak',
            'icon' => 'flame',
            'points_required' => 200,
            'condition' => 'streak:7',
        ],
        'style_guru' => [
            'name' => 'Style Guru',
            'description' => 'Fix 20 style issues',
            'icon' => 'palette',
            'points_required' => 150,
            'condition' => 'style_issues_fixed:20',
        ],
        'performance_hero' => [
            'name' => 'Performance Hero',
            'description' => 'Optimize 15 performance issues',
            'icon' => 'zap',
            'points_required' => 250,
            'condition' => 'performance_optimized:15',
        ],
        'security_guardian' => [
            'name' => 'Security Guardian',
            'description' => 'Fix 5 security vulnerabilities',
            'icon' => 'shield',
            'points_required' => 300,
            'condition' => 'security_fixed:5',
        ],
        'dedicated_learner' => [
            'name' => 'Dedicated Learner',
            'description' => 'Complete 30 submissions',
            'icon' => 'book',
            'points_required' => 350,
            'condition' => 'submissions:30',
        ],
    ];

    public function seedBadges()
    {
        foreach ($this->badges as $key => $badge) {
            Badge::updateOrCreate(
                ['name' => $badge['name']],
                $badge
            );
        }
    }

    public function checkAndAwardBadges(User $user)
    {
        $submissions = $user->submissions()->count();
        $reviews = $user->submissions()->whereHas('review')->count();

        if ($submissions >= 1 && !$user->badges()->where('name', 'Getting Started')->exists()) {
            $user->addBadge(Badge::where('name', 'Getting Started')->first()->id);
        }

        if ($submissions >= 30 && !$user->badges()->where('name', 'Dedicated Learner')->exists()) {
            $user->addBadge(Badge::where('name', 'Dedicated Learner')->first()->id);
        }

        if ($user->current_streak >= 7 && !$user->badges()->where('name', 'On Fire')->exists()) {
            $user->addBadge(Badge::where('name', 'On Fire')->first()->id);
        }
    }

    public function calculateUserStats(User $user)
    {
        $reviews = $user->submissions()->whereHas('review')->with('review')->get();

        $stats = [
            'perfect_scores' => 0,
            'bugs_found' => 0,
            'style_issues_fixed' => 0,
            'performance_optimized' => 0,
            'security_fixed' => 0,
        ];

        foreach ($reviews as $submission) {
            if ($submission->review) {
                if ($submission->review->overall_score >= 95) {
                    $stats['perfect_scores']++;
                }
                $stats['bugs_found'] += $submission->review->bugs_count ?? 0;
                $stats['style_issues_fixed'] += $submission->review->style_issues_count ?? 0;
                $stats['performance_optimized'] += $submission->review->performance_issues_count ?? 0;
                $stats['security_fixed'] += $submission->review->security_issues_count ?? 0;
            }
        }

        return $stats;
    }
}
