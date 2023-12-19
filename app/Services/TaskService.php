<?php

namespace App\Services;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct(private TaskRepository $repository)
    {}

    public function createFromRequest(CreateTaskRequest $request, User $user): Task
    {
        $payload = $request->toDTO();

        //check for parent task
        if ($payload->parentId) {
            $parent = $this->repository->getById($payload->parentId);

            if ($parent->userId !== $user->id) {
                abort(404);
            }
        }

        return $this->repository->create($payload, $user);
    }

    public function updateFromRequest(UpdateTaskRequest $request, User $user, int $id): Task
    {
        $payload = $request->toDTO();
        $task = $this->repository->getById($id);

        if ($task->userId !== $user->id) {
            abort(404);
        }

        return $this->repository->update($payload, $task);
    }
}
