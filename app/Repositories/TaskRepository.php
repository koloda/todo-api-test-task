<?php

namespace App\Repositories;

use App\DTO\CreateTaskDTO;
use App\DTO\UpdateTaskDTO;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getById(int $id): Task
    {
        return Task::query()->with('subtasks')->findOrFail($id);
    }

    public function getByUser(User $user): Collection
    {
        return Task::query()->where('userId', $user->id)->with('allSubtasks')->get();
    }

    public function getFlatSubtasksList(Task $task): array
    {
        $subtasks = [];
        foreach ($task->subtasks()->get() as $subtask) {
            $subtasks[] = $subtask;
            $subtasks = array_merge($subtasks, $this->getFlatSubtasksList($subtask));
        }

        return $subtasks;
    }
}
