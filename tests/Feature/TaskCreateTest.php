<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_task(): void
    {
        $user = \App\Models\User::factory()->create();
        $taskFields = \App\Models\Task::factory()->make()->toArray();
        unset($taskFields['userId']);
        unset($taskFields['parentId']);

        $this->actingAs($user)
            ->postJson('/api/tasks', $taskFields)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'priority',
                'status',
                'parentId',
                'userId',
            ]);

        $this->assertDatabaseHas('tasks', $taskFields);
    }

    public function test_create_task_and_subtask()
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create(['userId' => $user->id]);
        $subtaskFields = \App\Models\Task::factory()->make(['parentId' => $task->id])->toArray();
        unset($subtaskFields['userId']);

        $this->actingAs($user)
            ->postJson("/api/tasks", $subtaskFields)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'priority',
                'status',
                'parentId',
                'userId',
            ]);

        $this->assertDatabaseHas('tasks', $subtaskFields);
    }

    public function test_cannot_add_subtask_to_other_user_task()
    {
        $user = \App\Models\User::factory()->create();
        $secondUser = \App\Models\User::factory()->create();

        $task = \App\Models\Task::factory()->create(['userId' => $secondUser->id]);
        $subtaskFields = \App\Models\Task::factory()->make(['parentId' => $task->id])->toArray();
        unset($subtaskFields['userId']);

        $this->actingAs($user)
            ->postJson("/api/tasks", $subtaskFields)
            ->assertStatus(404);
    }
}
