<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function show(Review $review)
    {
        if ($review->submission->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($review->load('submission'));
    }

    public function forSubmission(Submission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review = $submission->review;

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        return response()->json($review);
    }

    public function recent(Request $request)
    {
        $limit = $request->get('limit', 5);

        $reviews = Review::whereHas('submission', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->with('submission')
        ->orderByDesc('created_at')
        ->limit($limit)
        ->get();

        return response()->json($reviews);
    }
}
