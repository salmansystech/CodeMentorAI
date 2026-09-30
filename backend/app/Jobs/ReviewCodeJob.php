<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Services\CodeReviewService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReviewCodeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Submission $submission)
    {
    }

    public function handle(CodeReviewService $reviewService)
    {
        try {
            $this->submission->update(['status' => 'processing']);
            $reviewService->reviewCode($this->submission);
        } catch (\Exception $e) {
            $this->submission->update(['status' => 'failed']);
            \Log::error('Code review failed: ' . $e->getMessage());
        }
    }
}
