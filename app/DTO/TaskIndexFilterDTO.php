<?php

namespace App\DTO;

use App\Models\TaskStatus;

class TaskIndexFilterDTO
{
    public function __construct(
        public ?TaskStatus $status = null,
        public ?int $priority = null,
        public ?string $text = null,
        public ?array $order = null,
    ) {
    }
}
