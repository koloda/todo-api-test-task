<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(version: '0.1', title: 'Task API')]
#[OA\Server(url: '/api')]
class TaskController extends Controller
{
    //add policy to controller
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            'complete' => 'complete',
        ]);
    }
    public const TASKS_PER_PAGE = 100;

    #[OA\Get(path: '/tasks', summary: 'Get list of tasks', tags: ['Tasks'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/TaskIndexRequest')]
    #[OA\Response(response: '200', description: 'Tasks', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/Task'),
        example: [
        [
            'id' => 1,
            'parentId' => null,
            'userId' => 1,
            'title' => 'Task 1',
            'description' => 'Description 1',
            'priority' => 3,
            'status' => 'todo',
            'createdAt' => '2021-01-01 00:00:00',
            'updatedAt' => '2021-01-01 00:00:00',
            'completedAt' => null,
            'subtasks' => [
                [
                    'id' => 2,
                    'parentId' => 1,
                    'userId' => 1,
                    'title' => 'Task 2',
                    'description' => 'Description 2',
                    'priority' => 2,
                    'status' => 'done',
                    'createdAt' => '2021-01-01 00:00:00',
                    'updatedAt' => '2021-01-01 00:00:00',
                    'completedAt' => '2021-01-01 00:00:00',
                    'subtasks' => [],
                ],
            ],
        ],
    ]
    ))]
    public function index(TaskRepository $repository, TaskIndexRequest $request): Collection
    {
        $user = auth()->user();
        $filter = $request->toDTO();

        return $repository->getByUser($user, $filter)->get();
    }

    #[OA\Get(path: '/tasks/{id}', summary: 'Get task by ID', tags: ['Tasks'])]
    #[OA\PathParameter(name: 'id', description: 'Task ID', required: true)]
    #[OA\Response(ref: '#/components/schemas/Task', response: '200', description: 'Task')]
    public function show(Task $task): Task
    {
        return $task->load('subtasks');
    }

    #[OA\Post(path: '/tasks', summary: 'Create task', tags: ['Tasks'])]
    #[OA\RequestBody(ref: '#/components/requestBodies/CreateTaskRequest')]
    #[OA\Response(ref: '#/components/schemas/Task', response: '201', description: 'Task')]
    public function store(TaskService $service, CreateTaskRequest $request): Task
    {
        return $service->create($request->toDTO(), $request->user());
    }

    #[OA\Put(path: '/tasks/{id}', summary: 'Update task', tags: ['Tasks'])]
    #[OA\PathParameter(name: 'id', description: 'Task ID', required: true)]
    #[OA\RequestBody(ref: '#/components/requestBodies/UpdateTaskRequest')]
    #[OA\Response(ref: '#/components/schemas/Task', response: '200', description: 'Task')]
    public function update(TaskService $service, UpdateTaskRequest $request, Task $task): Task
    {
        return $service->update($request->toDTO(), $task);
    }

    #[OA\Delete(path: '/tasks/{id}', summary: 'Delete task', tags: ['Tasks'])]
    #[OA\PathParameter(name: 'id', description: 'Task ID', required: true)]
    #[OA\Response(response: '204', description: 'No content')]
    public function destroy(TaskService $service, Task $task): JsonResponse
    {
        return response()->json($service->delete($task), 204);
    }

    #[OA\Post(path: '/tasks/{id}/complete', summary: 'Complete task', tags: ['Tasks'])]
    #[OA\PathParameter(name: 'id', description: 'Task ID', required: true)]
    #[OA\Response(ref: '#/components/schemas/Task', response: '200', description: 'Task')]
    public function complete(TaskService $service, Task $task): Task
    {
        return $service->complete($task);
    }
}
