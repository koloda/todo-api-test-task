<?php

namespace App\DTO;

use App\Models\TaskStatus;

class UpdateTaskDTO
{
    public function __construct(
        public string $title,
        public int $priority,
        public ?string $description,
    ) {}
}
