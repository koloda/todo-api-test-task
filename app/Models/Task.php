<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string title
 * @property ?string description
 * @property ?int parentId
 * @property int userId
 * @property int priority
 * @property TaskStatus status
 */
class Task extends Model
{
    use HasFactory;

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'status' => TaskStatus::class,
    ];

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parentId')->with('subtasks');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
