<?php

namespace App\DTO;

use App\Models\TaskStatus;

class CreateTaskDTO
{
    public function __construct(
        public string $title,
        public int $priority,
        public TaskStatus $status,
        public ?string $description,
        public ?int $parentId,
    ) {}
}
