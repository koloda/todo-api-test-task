<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task);
    }

    public function complete(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task);
    }

    private function isOwner(User $user, Task $task): bool
    {
        return $user->id === $task->userId;
    }
}
