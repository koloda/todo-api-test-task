<?php

namespace App\Http\Requests;

enum TaskOrder: string
{
    case CREATED_ASC = 'createdAt.asc';
    case CREATED_DESC = 'createdAt.desc';
    case COMPLETED_ASC = 'completedAt.asc';
    case COMPLETED_DESC = 'completedAt.desc';
    case PRIORITY_ASC = 'priority.asc';
    case PRIORITY_DESC = 'priority.desc';

    public function toSql(): string
    {
        return str_replace('.', ' ', $this->value);
    }
}
