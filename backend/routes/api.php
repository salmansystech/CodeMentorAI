<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\LeaderboardController;

Route::middleware('api')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        Route::resource('submissions', SubmissionController::class);
        Route::get('/submissions/{submission}/review', [ReviewController::class, 'forSubmission']);

        Route::get('/reviews/{review}', [ReviewController::class, 'show']);
        Route::get('/reviews/recent', [ReviewController::class, 'recent']);

        Route::get('/progress/dashboard', [ProgressController::class, 'dashboard']);
        Route::get('/progress/stats', [ProgressController::class, 'stats']);

        Route::get('/leaderboard/global', [LeaderboardController::class, 'global']);
        Route::get('/leaderboard/level', [LeaderboardController::class, 'byLevel']);
        Route::get('/leaderboard/streak', [LeaderboardController::class, 'byStreak']);
    });
});
