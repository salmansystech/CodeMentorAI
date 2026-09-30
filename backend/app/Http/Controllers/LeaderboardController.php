<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function global(Request $request)
    {
        $limit = $request->get('limit', 50);
        $timeframe = $request->get('timeframe', 'all');

        $query = User::orderByDesc('total_points');

        if ($timeframe === 'week') {
            $query->where('updated_at', '>=', now()->subWeek());
        } elseif ($timeframe === 'month') {
            $query->where('updated_at', '>=', now()->subMonth());
        }

        $users = $query->paginate($limit);

        $userPosition = User::where('total_points', '>', auth()->user()->total_points)
            ->count() + 1;

        return response()->json([
            'leaderboard' => $users,
            'user_position' => $userPosition,
            'user_stats' => [
                'points' => auth()->user()->total_points,
                'level' => auth()->user()->level,
            ],
        ]);
    }

    public function byLevel(Request $request)
    {
        $level = $request->get('level', 1);
        $limit = $request->get('limit', 50);

        $users = User::where('level', $level)
            ->orderByDesc('total_points')
            ->paginate($limit);

        return response()->json($users);
    }

    public function byStreak(Request $request)
    {
        $limit = $request->get('limit', 50);

        $users = User::orderByDesc('current_streak')
            ->paginate($limit);

        return response()->json($users);
    }
}
