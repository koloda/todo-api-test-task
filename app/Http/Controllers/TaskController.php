<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Collection;

class TaskController extends Controller
{
    public function index(TaskRepository $repository): Collection
    {
        $user = auth()->user();

        return $repository->getByUser($user)->get();
    }

    public function show(TaskRepository $repository, int $id): Task
    {
        $user = auth()->user();
        $task = $repository->getById($id);

        if ($task->userId !== $user->id) {
            abort(404);
        }

        return $task;
    }

    public function store(TaskService $service, CreateTaskRequest $request): Task
    {
        return $service->createFromRequest($request, auth()->user());
    }

    public function update(TaskService $service, UpdateTaskRequest $request, int $id): Task
    {
        return $service->updateFromRequest($request, auth()->user(), $id);
    }

    public function destroy(TaskService $service, int $id): bool|null
    {
        return $service->deleteById($id, auth()->user());
    }
}
