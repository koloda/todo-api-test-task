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

    public function create(CreateTaskDTO $payload, User $user): Task
    {
        $task = new Task();
        $task->title = $payload->title;
        $task->description = $payload->description;
        $task->userId = $user->id;
        $task->parentId = $payload->parentId;
        $task->priority = $payload->priority;
        $task->status = $payload->status;
        $task->save();

        return $task;
    }

    public function update(UpdateTaskDTO $payload, Task $task): Task
    {
        $task->title = $payload->title;
        $task->description = $payload->description;
        $task->priority = $payload->priority;
        $task->save();

        return $task;
    }
}
