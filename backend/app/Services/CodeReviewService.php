<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Review;
use App\Models\Resource;
use Anthropic;

class CodeReviewService
{
    protected $client;

    public function __construct()
    {
        $this->client = Anthropic::client(config('services.anthropic.api_key'));
    }

    public function reviewCode(Submission $submission)
    {
        $prompt = $this->buildReviewPrompt($submission);

        $response = $this->client->messages()->create([
            'model' => 'claude-opus-4-1',
            'max_tokens' => 2000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        $reviewData = $this->parseReviewResponse($response->content[0]->text);

        return $this->saveReview($submission, $reviewData);
    }

    protected function buildReviewPrompt(Submission $submission)
    {
        return <<<PROMPT
You are an expert code reviewer. Analyze the following {$submission->language} code and provide a detailed review.

Code:
```{$submission->language}
{$submission->code}
```

Please provide a JSON response with the following structure:
{
    "overall_score": 0-100,
    "bugs": [
        {
            "line": number,
            "type": "critical|warning|info",
            "message": "description",
            "explanation": "detailed explanation",
            "fix": "suggested fix"
        }
    ],
    "style_issues": [
        {
            "line": number,
            "issue": "description",
            "suggestion": "suggested improvement"
        }
    ],
    "performance": [
        {
            "line": number,
            "issue": "description",
            "impact": "description of performance impact",
            "suggestion": "optimization suggestion"
        }
    ],
    "security": [
        {
            "line": number,
            "vulnerability": "description",
            "risk": "risk level (high|medium|low)",
            "fix": "suggested fix"
        }
    ],
    "summary": "brief summary of the code review",
    "key_strengths": ["strength1", "strength2"],
    "areas_for_improvement": ["area1", "area2"]
}

Only return the JSON, no additional text.
PROMPT;
    }

    protected function parseReviewResponse($content)
    {
        try {
            return json_decode($content, true);
        } catch (\Exception $e) {
            return [
                'overall_score' => 50,
                'bugs' => [],
                'style_issues' => [],
                'performance' => [],
                'security' => [],
                'summary' => 'Review parsing error',
            ];
        }
    }

    protected function saveReview(Submission $submission, $reviewData)
    {
        $review = Review::create([
            'submission_id' => $submission->id,
            'findings' => $reviewData,
            'overall_score' => $reviewData['overall_score'] ?? 50,
            'bugs_count' => count($reviewData['bugs'] ?? []),
            'style_issues_count' => count($reviewData['style_issues'] ?? []),
            'performance_issues_count' => count($reviewData['performance'] ?? []),
            'security_issues_count' => count($reviewData['security'] ?? []),
        ]);

        $this->generateLearningResources($submission, $reviewData);
        $this->awardPoints($submission->user, $reviewData);

        $submission->update(['status' => 'completed']);

        return $review;
    }

    protected function generateLearningResources(Submission $submission, $reviewData)
    {
        $issues = array_merge(
            $reviewData['bugs'] ?? [],
            $reviewData['style_issues'] ?? [],
            $reviewData['performance'] ?? [],
            $reviewData['security'] ?? []
        );

        if (empty($issues)) {
            return;
        }

        $topIssues = array_slice($issues, 0, 3);

        foreach ($topIssues as $issue) {
            Resource::create([
                'submission_id' => $submission->id,
                'title' => 'Learn: ' . ($issue['message'] ?? $issue['issue'] ?? 'Code Improvement'),
                'content' => $this->generateResourceContent($issue, $submission->language),
                'type' => 'tutorial',
                'category' => $this->categorizeIssue($issue),
                'difficulty_level' => 'beginner',
            ]);
        }
    }

    protected function generateResourceContent($issue, $language)
    {
        return "Learn how to fix: " . ($issue['message'] ?? $issue['issue'] ?? '') . "\n\n" .
               "Explanation: " . ($issue['explanation'] ?? $issue['suggestion'] ?? '') . "\n\n" .
               "Language: " . $language;
    }

    protected function categorizeIssue($issue)
    {
        if (isset($issue['type']) && $issue['type'] === 'critical') {
            return 'bugs';
        }
        if (isset($issue['vulnerability'])) {
            return 'security';
        }
        if (isset($issue['impact'])) {
            return 'performance';
        }
        return 'style';
    }

    protected function awardPoints($user, $reviewData)
    {
        $points = 10;
        $score = $reviewData['overall_score'] ?? 50;

        if ($score >= 90) {
            $points += 50;
        } elseif ($score >= 80) {
            $points += 30;
        } elseif ($score >= 70) {
            $points += 15;
        }

        $user->addPoints($points);
    }
}
