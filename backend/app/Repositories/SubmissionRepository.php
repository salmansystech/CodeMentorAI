<?php

namespace App\Repositories;

use App\Models\Submission;
use App\Services\QueryOptimizationService;
use Illuminate\Pagination\Paginator;

class SubmissionRepository
{
    protected $model;

    public function __construct(Submission $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model->where('id', $id)
        )->firstOrFail();
    }

    public function findByUserAndId($userId, $submissionId)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model->where('user_id', $userId)->where('id', $submissionId)
        )->firstOrFail();
    }

    public function getUserSubmissions($userId, $perPage = 15)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model->where('user_id', $userId)
        )
        ->orderByDesc('submitted_at')
        ->paginate($perPage);
    }

    public function getByLanguage($language, $perPage = 15)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model->where('language', $language)
        )
        ->orderByDesc('created_at')
        ->paginate($perPage);
    }

    public function getByStatus($status, $perPage = 15)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model->where('status', $status)
        )
        ->orderByDesc('created_at')
        ->paginate($perPage);
    }

    public function create($userId, array $data)
    {
        return $this->model->create([
            'user_id' => $userId,
            'code' => $data['code'],
            'language' => $data['language'],
            'title' => $data['title'] ?? 'Untitled',
            'description' => $data['description'] ?? null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);
    }

    public function update($id, array $data)
    {
        $submission = $this->findById($id);
        $submission->update($data);
        return $submission;
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getRecentSubmissions($limit = 10)
    {
        return QueryOptimizationService::optimizeSubmissionQuery(
            $this->model
        )
        ->orderByDesc('created_at')
        ->limit($limit)
        ->get();
    }

    public function countByUser($userId)
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function countByStatus($status)
    {
        return $this->model->where('status', $status)->count();
    }
}
