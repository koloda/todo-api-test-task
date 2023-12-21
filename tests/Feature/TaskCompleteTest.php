<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;

class TaskCompleteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_complete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'userId' => $user->id,
            'status' => TaskStatus::Todo,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Todo,
            'completedAt' => null,
        ]);

        $this->actingAs($user)
            ->postJson('/api/tasks/' . $task->id . '/complete')
            ->assertStatus(200);

        $task->refresh();
        $this->assertEquals(TaskStatus::Done, $task->status);
        $this->assertNotNull($task->completedAt);
    }

    public function test_complete_task_with_subtask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'userId' => $user->id,
            'status' => TaskStatus::Todo,
        ]);
        $subtask = Task::factory()->create([
            'userId' => $user->id,
            'parentId' => $task->id,
            'status' => TaskStatus::Todo,
        ]);

        $this->actingAs($user)
            ->postJson('/api/tasks/' . $subtask->id . '/complete')
            ->assertStatus(200);

        $this->actingAs($user)
            ->postJson('/api/tasks/' . $task->id . '/complete')
            ->assertStatus(200);

        $task->refresh();
        $this->assertEquals(TaskStatus::Done, $task->status);
        $this->assertNotNull($task->completedAt);

        $subtask->refresh();
        $this->assertEquals(TaskStatus::Done, $subtask->status);
        $this->assertNotNull($subtask->completedAt);
    }

    public function test_cannot_complete_tasks_with_not_completed_subtasks()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'userId' => $user->id,
            'status' => TaskStatus::Todo,
        ]);
        $subtask = Task::factory()->create([
            'userId' => $user->id,
            'parentId' => $task->id,
            'status' => TaskStatus::Todo,
        ]);

        $this->actingAs($user)
            ->postJson('/api/tasks/' . $task->id . '/complete')
            ->assertStatus(400);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Todo,
        ]);
    }

    public function test_cannot_complete_other_user_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create([
            'userId' => $otherUser->id,
            'status' => TaskStatus::Todo,
        ]);

        $this->actingAs($user)
            ->postJson('/api/tasks/' . $task->id . '/complete')
            ->assertStatus(404);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Todo,
        ]);
    }
}
