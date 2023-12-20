<?php

namespace App\Repositories;

use App\DTO\TaskIndexFilterDTO;
use App\Http\Requests\TaskOrder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository
{
    public function getById(int $id): Task
    {
        return Task::query()->with('subtasks')->findOrFail($id);
    }

    public function getByUser(User $user, ?TaskIndexFilterDTO $filter): Builder
    {
        $query = Task::query()->where('userId', $user->id)->with('subtasks');
        if ($filter) {
            $query = $this->applyFilter($query, $filter);
        }

        return $query;
    }

    private function applyFilter(Builder $query, TaskIndexFilterDTO $filter): Builder
    {
        if ($filter->status) {
            $query->where('status', $filter->status->value);
        }

        if ($filter->priority) {
            $query->where('priority', $filter->priority);
        }

        if ($filter->text) {
            $query->where(function (Builder $query) use ($filter) {
                $query->whereFullText('title', $filter->text)
                    ->orWhereFullText('description', $filter->text);
            });
        }

        if ($filter->order) {
            foreach ($filter->order as $orderOption) {
                /** @var TaskOrder $orderOption */
                $query->orderByRaw($orderOption->toSql());
            }
        }

        return $query;
    }

    public function getFlatSubtasksList(Task $task): array
    {
        $subtasks = [];
        foreach ($task->subtasks()->get() as $subtask) {
            $subtasks[] = $subtask;
            $subtasks = array_merge($subtasks, $this->getFlatSubtasksList($subtask));
        }

        return $subtasks;
    }
}
