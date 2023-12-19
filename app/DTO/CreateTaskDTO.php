<?php

namespace App\DTO;

class CreateTaskDTO
{
    public function __construct(
        public string $title,
        public int $priority,
        public ?string $description,
        public ?int $parentId,
    ) {}
}
