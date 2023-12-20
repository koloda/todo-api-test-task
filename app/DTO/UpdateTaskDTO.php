<?php

namespace App\DTO;

class UpdateTaskDTO
{
    public function __construct(
        public string $title,
        public int $priority,
        public ?string $description,
    ) {
    }
}
