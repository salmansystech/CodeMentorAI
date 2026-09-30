<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\QueryOptimizationService;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findById($id)
    {
        return QueryOptimizationService::optimizeUserQuery(
            $this->model->where('id', $id)
        )->firstOrFail();
    }

    public function findByEmail($email)
    {
        return QueryOptimizationService::optimizeUserQuery(
            $this->model->where('email', $email)
        )->firstOrFail();
    }

    public function getLeaderboard($perPage = 50)
    {
        return QueryOptimizationService::optimizeLeaderboardQuery(
            $this->model
        )
        ->paginate($perPage);
    }

    public function getLeaderboardByLevel($level, $perPage = 50)
    {
        return QueryOptimizationService::optimizeLeaderboardQuery(
            $this->model->where('level', $level)
        )
        ->paginate($perPage);
    }

    public function getLeaderboardByStreak($perPage = 50)
    {
        return $this->model
            ->select('id', 'name', 'current_streak', 'best_streak', 'total_points')
            ->orderByDesc('current_streak')
            ->paginate($perPage);
    }

    public function getTopUsers($limit = 10)
    {
        return QueryOptimizationService::optimizeUserQuery(
            $this->model
        )
        ->orderByDesc('total_points')
        ->limit($limit)
        ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->findById($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function incrementPoints($userId, $points)
    {
        $user = $this->findById($userId);
        $user->addPoints($points);
        return $user;
    }

    public function incrementStreak($userId)
    {
        $user = $this->findById($userId);
        $user->current_streak += 1;

        if ($user->current_streak > $user->best_streak) {
            $user->best_streak = $user->current_streak;
        }

        $user->save();
        return $user;
    }

    public function resetStreak($userId)
    {
        $user = $this->findById($userId);
        $user->current_streak = 0;
        $user->save();
        return $user;
    }

    public function count()
    {
        return $this->model->count();
    }

    public function getTotalPoints($userId)
    {
        return $this->model->where('id', $userId)->value('total_points');
    }

    public function getLevel($userId)
    {
        return $this->model->where('id', $userId)->value('level');
    }
}
