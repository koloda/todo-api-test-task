<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;

class TaskController extends Controller
{
    public const TASKS_PER_PAGE = 100;

    public function index(TaskRepository $repository, TaskIndexRequest $request)
    {
        $user = auth()->user();
        $filter = $request->toDTO();

        return $repository->getByUser($user, $filter)->get();
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

    public function complete(TaskService $service, int $id): Task
    {
        return $service->completeById($id, auth()->user());
    }
}
