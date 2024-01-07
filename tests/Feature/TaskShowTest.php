<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskShowTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_show_user_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['userId' => $user->id]);

        $this->actingAs($user)->getJson("/api/tasks/{$task->id}")
            ->assertOk()
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'parentId' => $task->parentId,
                'userId' => $task->userId,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'parentId' => $task->parentId,
            'userId' => $task->userId,
        ]);
    }

    public function test_cannot_see_another_user_task()
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();
        $task = Task::factory()->create(['userId' => $secondUser->id]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'parentId' => $task->parentId,
            'userId' => $task->userId,
        ]);

        $this->actingAs($user)
            ->getJson("/api/tasks/{$task->id}")
            ->assertStatus(403);
    }

    public function test_show_task_with_subtasks()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['userId' => $user->id]);
        $subtask = Task::factory()->create(['parentId' => $task->id, 'userId' => $user->id]);
        $secondLevelSubtask = Task::factory()->create(['parentId' => $subtask->id, 'userId' => $user->id]);

        $this->actingAs($user)
            ->getJson("/api/tasks/{$task->id}")
            ->assertOk()
            ->assertJson([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'parentId' => $task->parentId,
                'userId' => $task->userId,
                'subtasks' => [
                    [
                        'id' => $subtask->id,
                        'title' => $subtask->title,
                        'description' => $subtask->description,
                        'parentId' => $subtask->parentId,
                        'userId' => $subtask->userId,
                        'subtasks' => [
                            [
                                'id' => $secondLevelSubtask->id,
                                'title' => $secondLevelSubtask->title,
                                'description' => $secondLevelSubtask->description,
                                'parentId' => $secondLevelSubtask->parentId,
                                'userId' => $secondLevelSubtask->userId,
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
