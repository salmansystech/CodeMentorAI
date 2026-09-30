<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'CodeMentor AI'),
        'version' => '1.0.0',
        'environment' => env('APP_ENV', 'production'),
    ],

    'api' => [
        'rate_limit_authenticated' => 100,
        'rate_limit_unauthenticated' => 30,
        'rate_limit_window' => 60,
        'max_request_size' => 50000,
    ],

    'cache' => [
        'user_stats_duration' => 600,
        'leaderboard_duration' => 300,
        'badge_duration' => 1800,
        'general_duration' => 3600,
    ],

    'code_review' => [
        'max_code_length' => 50000,
        'max_title_length' => 255,
        'max_description_length' => 1000,
        'supported_languages' => [
            'python',
            'javascript',
            'typescript',
            'java',
            'cpp',
            'php',
            'go',
            'rust',
            'sql',
        ],
        'ai_model' => env('AI_MODEL', 'claude-opus-4-1'),
        'ai_timeout' => 30,
    ],

    'gamification' => [
        'points' => [
            'submission' => 10,
            'perfect_score' => 50,
            'high_score' => 30,
            'medium_score' => 15,
        ],
        'levels' => [
            1 => 0,
            10 => 500,
            20 => 1000,
            30 => 2500,
            40 => 5000,
            50 => 10000,
        ],
    ],

    'performance' => [
        'query_timeout' => 30,
        'slow_query_threshold' => 1,
        'max_paginate_limit' => 100,
        'default_paginate_limit' => 15,
    ],

    'features' => [
        'rate_limiting' => true,
        'caching' => true,
        'error_logging' => true,
        'performance_monitoring' => true,
        'user_tracking' => true,
    ],

    'email' => [
        'notifications_enabled' => true,
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@codementor.ai'),
        'from_name' => env('MAIL_FROM_NAME', 'CodeMentor AI'),
    ],
];
