<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Submission;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $type = $request->get('type');

        $query = Resource::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $resources = $query->paginate(20);

        return response()->json($resources);
    }

    public function show(Resource $resource)
    {
        return response()->json($resource);
    }

    public function forSubmission(Submission $submission)
    {
        if ($submission->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $resources = $submission->resources()
            ->orderByDesc('created_at')
            ->get();

        return response()->json($resources);
    }

    public function byCategory(Request $request)
    {
        $category = $request->get('category');
        $limit = $request->get('limit', 10);

        $resources = Resource::where('category', $category)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json($resources);
    }
}
