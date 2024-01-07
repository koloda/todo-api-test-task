<?php

namespace Tests\Feature;

use Tests\TestCase;

class TaskDestroyTest extends TestCase
{
    public function test_destroy_task()
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create(['userId' => $user->id]);

        $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}")->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_destroy_task_with_subtasks()
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create(['userId' => $user->id]);
        $subtask = \App\Models\Task::factory()->create(['parentId' => $task->id]);
        $secondLevelTask = \App\Models\Task::factory()->create(['parentId' => $subtask->id]);

        $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}")->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $subtask->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $secondLevelTask->id]);
    }

    public function test_cannot_destroy_other_user_task()
    {
        $user = \App\Models\User::factory()->create();
        $task = \App\Models\Task::factory()->create();

        $this->actingAs($user)->deleteJson("/api/tasks/{$task->id}")->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
