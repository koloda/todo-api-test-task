<?php

namespace App\Models;

enum TaskStatus: string
{
    case Todo = 'todo';
    case Done = 'done';
}
