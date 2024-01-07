<?php

namespace Tests\Feature;

use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_update_task(): void
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create(['userId' => $user->id]);
        $taskFields = \App\Models\Task::factory()->make()->toArray();
        unset($taskFields['userId']);
        unset($taskFields['parentId']);

        $this->actingAs($user)
            ->putJson("/api/tasks/{$task->id}", $taskFields)
            ->assertStatus(200)
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

    public function test_cannot_update_other_user_task()
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create();
        $taskFields = \App\Models\Task::factory()->make()->toArray();
        unset($taskFields['userId']);
        unset($taskFields['parentId']);

        $this->actingAs($user)
            ->putJson("/api/tasks/{$task->id}", $taskFields)
            ->assertStatus(403);
    }
}
