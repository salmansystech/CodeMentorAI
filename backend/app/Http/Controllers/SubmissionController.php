<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Services\CodeReviewService;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    protected $reviewService;

    public function __construct(CodeReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $submissions = auth()->user()->submissions()
            ->with('review')
            ->orderByDesc('submitted_at')
            ->paginate(10);

        return response()->json($submissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'language' => 'required|string|in:python,javascript,java,cpp,php,go,rust,sql',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $submission = auth()->user()->submissions()->create([
            'code' => $validated['code'],
            'language' => $validated['language'],
            'title' => $validated['title'] ?? 'Untitled Submission',
            'description' => $validated['description'] ?? '',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        dispatch(new \App\Jobs\ReviewCodeJob($submission));

        return response()->json([
            'message' => 'Code submitted successfully',
            'submission' => $submission,
        ], 201);
    }

    public function show(Submission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($submission->load('review', 'resources'));
    }

    public function destroy(Submission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $submission->delete();

        return response()->json([
            'message' => 'Submission deleted successfully',
        ]);
    }
}
