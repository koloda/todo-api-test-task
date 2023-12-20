<?php

namespace App\Services;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Repositories\TaskRepository;

readonly class TaskService
{
    public function __construct(private TaskRepository $repository)
    {
    }

    public function createFromRequest(CreateTaskRequest $request, User $user): Task
    {
        $payload = $request->toDTO();

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

    public function updateFromRequest(UpdateTaskRequest $request, User $user, int $id): Task
    {
        $payload = $request->toDTO();
        $task = $this->repository->getById($id);

        if ($task->userId !== $user->id) {
            abort(404);
        }

        $task->title = $payload->title;
        $task->description = $payload->description;
        $task->priority = $payload->priority;
        $task->save();

        return $task;
    }

    public function deleteById(int $id, User $user): ?bool
    {
        $task = $this->repository->getById($id);

        if ($task->userId !== $user->id) {
            abort(404);
        }

        return $task->delete();
    }

    public function completeById(int $id, User $user): Task
    {
        $task = $this->repository->getById($id);

        if ($task->userId !== $user->id) {
            abort(404);
        }

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
