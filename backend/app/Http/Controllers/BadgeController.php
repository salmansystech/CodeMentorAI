<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::all();

        return response()->json($badges);
    }

    public function userBadges()
    {
        $user = auth()->user();
        $badges = $user->badges()
            ->withPivot('earned_at')
            ->orderByDesc('earned_at')
            ->get();

        return response()->json($badges);
    }

    public function availableBadges()
    {
        $user = auth()->user();
        $userBadgeIds = $user->badges()->pluck('badge_id')->toArray();

        $availableBadges = Badge::whereNotIn('id', $userBadgeIds)
            ->where('points_required', '<=', $user->total_points)
            ->get();

        return response()->json($availableBadges);
    }

    public function show(Badge $badge)
    {
        $users = $badge->users()
            ->orderByDesc('user_badges.earned_at')
            ->paginate(20);

        return response()->json([
            'badge' => $badge,
            'users' => $users,
        ]);
    }
}
