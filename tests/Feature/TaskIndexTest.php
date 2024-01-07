<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_list_user_tasks()
    {
        Task::query()->whereNotNull('id')->delete();
        $user = User::factory()->create();
        Task::factory()->count(13)->create(['userId' => $user->id]);
        $secondUser = User::factory()->create();
        Task::factory()->count(5)->create(['userId' => $secondUser->id]);

        $this->actingAs($user)
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(13);
    }
}
