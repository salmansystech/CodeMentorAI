<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $submissions = $user->submissions()->count();
        $reviews = $user->submissions()->whereHas('review')->count();
        $totalPoints = $user->total_points;
        $level = $user->level;
        $badges = $user->badges()->count();

        $recentSubmissions = $user->submissions()
            ->with('review')
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        return response()->json([
            'user' => $user,
            'stats' => [
                'total_submissions' => $submissions,
                'reviewed_submissions' => $reviews,
                'total_points' => $totalPoints,
                'level' => $level,
                'badges_earned' => $badges,
                'current_streak' => $user->current_streak,
                'best_streak' => $user->best_streak,
            ],
            'recent_submissions' => $recentSubmissions,
        ]);
    }

    public function stats()
    {
        $user = auth()->user();

        $submissions = $user->submissions()
            ->with('review')
            ->orderByDesc('submitted_at')
            ->get();

        $issueTypes = [
            'bugs' => 0,
            'style' => 0,
            'performance' => 0,
            'security' => 0,
        ];

        foreach ($submissions as $submission) {
            if ($submission->review) {
                $issueTypes['bugs'] += $submission->review->bugs_count ?? 0;
                $issueTypes['style'] += $submission->review->style_issues_count ?? 0;
                $issueTypes['performance'] += $submission->review->performance_issues_count ?? 0;
                $issueTypes['security'] += $submission->review->security_issues_count ?? 0;
            }
        }

        return response()->json([
            'stats' => $issueTypes,
            'improvement_rate' => $this->calculateImprovementRate($submissions),
            'languages_used' => $user->submissions()
                ->groupBy('language')
                ->selectRaw('language, count(*) as count')
                ->get(),
        ]);
    }

    private function calculateImprovementRate($submissions)
    {
        if (count($submissions) < 2) {
            return 0;
        }

        $first = $submissions->last();
        $last = $submissions->first();

        if ($first->review && $last->review) {
            $improvement = (($first->review->overall_score - $last->review->overall_score) / $first->review->overall_score) * 100;
            return round($improvement, 2);
        }

        return 0;
    }
}
