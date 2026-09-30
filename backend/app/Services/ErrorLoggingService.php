<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ErrorLoggingService
{
    public static function logCodeReviewError($submissionId, $error)
    {
        Log::error("Code review failed for submission {$submissionId}", [
            'error' => $error->getMessage(),
            'trace' => $error->getTraceAsString(),
            'submission_id' => $submissionId,
            'timestamp' => now(),
        ]);
    }

    public static function logAPIError($endpoint, $statusCode, $error)
    {
        Log::warning("API error on {$endpoint}", [
            'status' => $statusCode,
            'error' => $error,
            'timestamp' => now(),
        ]);
    }

    public static function logPerformanceWarning($metric, $value, $threshold)
    {
        if ($value > $threshold) {
            Log::warning("Performance warning: {$metric} exceeded threshold", [
                'metric' => $metric,
                'value' => $value,
                'threshold' => $threshold,
                'timestamp' => now(),
            ]);
        }
    }

    public static function logUserAction($userId, $action, $data = [])
    {
        Log::info("User action: {$action}", [
            'user_id' => $userId,
            'action' => $action,
            'data' => $data,
            'timestamp' => now(),
        ]);
    }
}
