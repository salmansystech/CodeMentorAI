<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class QueryOptimizationService
{
    public static function optimizeSubmissionQuery(Builder $query)
    {
        return $query->with(['review', 'resources'])
            ->select('id', 'user_id', 'code', 'language', 'title', 'status', 'submitted_at', 'created_at');
    }

    public static function optimizeUserQuery(Builder $query)
    {
        return $query->select('id', 'name', 'email', 'level', 'total_points', 'current_streak', 'best_streak', 'created_at');
    }

    public static function optimizeReviewQuery(Builder $query)
    {
        return $query->with('submission')
            ->select('id', 'submission_id', 'findings', 'overall_score', 'bugs_count', 'style_issues_count', 'performance_issues_count', 'security_issues_count', 'created_at');
    }

    public static function optimizeLeaderboardQuery(Builder $query)
    {
        return $query->select('id', 'name', 'level', 'total_points', 'current_streak', 'best_streak')
            ->orderByDesc('total_points')
            ->limit(100);
    }

    public static function optimizeBadgeQuery(Builder $query)
    {
        return $query->select('id', 'name', 'description', 'icon', 'points_required');
    }
}
