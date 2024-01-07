<?php

namespace App\Services;

use App\DTO\CreateTaskDTO;
use App\DTO\UpdateTaskDTO;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Repositories\TaskRepository;

readonly class TaskService
{
    public function __construct(private TaskRepository $repository)
    {
    }

    public function create(CreateTaskDTO $payload, User $user): Task
    {
        if ($payload->parentId) {
            $parent = $this->repository->getById($payload->parentId);

            if ($parent->userId !== $user->id) {
                abort(404);
            }
        }

        $task = new Task();
        $task->title = $payload->title;
        $task->description = $payload->description;
        $task->userId = $user->id;
        $task->parentId = $payload->parentId;
        $task->priority = $payload->priority;
        $task->status = TaskStatus::Todo;
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

    public function delete(Task $task): ?bool
    {
        return $task->delete();
    }

    public function complete(Task $task): Task
    {
        if ($task->status === TaskStatus::Done) {
            abort(400);
        }

        foreach ($this->repository->getFlatSubtasksList($task) as $subtask) {
            if ($subtask->status !== TaskStatus::Done) {
                abort(400);
            }
        }

        $task->status = TaskStatus::Done;
        $task->completedAt = now();
        $task->save();

        return $task;
    }
}
