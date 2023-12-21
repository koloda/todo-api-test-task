<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TaskFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_user_tasks_with_filters_and_order()
    {
        $user = User::factory()->create();
        Task::factory()->count(5)->create(['userId' => $user->id, 'status' => 'todo', 'priority' => 3, 'title' => 'task']);
        Task::factory()->count(5)->create(['userId' => $user->id, 'status' => 'todo', 'priority' => 3, 'description' => 'task']);
        Task::factory()->count(5)->create(['userId' => $user->id, 'status' => 'todo', 'priority' => 3, 'title' => 'task', 'description' => 'task']);

        Task::factory()->count(5)->create(['userId' => $user->id, 'status' => 'done', 'priority' => 3, 'title' => 'task', 'description' => 'task']);
        Task::factory()->count(5)->create(['userId' => $user->id, 'status' => 'todo', 'priority' => 5, 'title' => 'task']);

        DB::commit();

        $response = $this->actingAs($user)
            ->getJson('/api/tasks?status=todo&priority=3&text=task&order=priority.desc,createdAt.asc')
            ->assertJsonCount(15);

        //check order
        $tasks = $response->json();
        $prevTask = $tasks[0];
        for ($i = 1; $i < 15; $i++) {
            $task = $tasks[$i];
            $this->assertTrue($task['priority'] <= $prevTask['priority']);
            $this->assertTrue($task['createdAt'] >= $prevTask['createdAt']);
            $prevTask = $task;
        }

        Task::query()->truncate();
    }
}
