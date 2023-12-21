<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        foreach (\App\Models\User::all() as $user) {
            \App\Models\Task::factory(20)->create(['userId' => $user->id]);
        }
    }
}
