<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

/**
 * @property int id
 * @property ?int parentId
 * @property int userId
 * @property string title
 * @property ?string description
 * @property int priority
 * @property TaskStatus status
 * @property Carbon createdAt
 * @property Carbon updatedAt
 * @property ?Carbon completedAt
 *
 * @property-read Task[] subtasks
 */
#[OA\Schema(schema: 'Task')]
class Task extends Model
{
    use HasFactory;

    #[OA\Property(property: 'id', type: 'integer')]
    #[OA\Property(property: 'parentId', type: 'integer')]
    #[OA\Property(property: 'userId', type: 'integer')]
    #[OA\Property(property: 'title', type: 'string')]
    #[OA\Property(property: 'description', type: 'string')]
    #[OA\Property(property: 'priority', type: 'integer')]
    #[OA\Property(property: 'status', type: 'string', enum: [TaskStatus::Todo, TaskStatus::Done])]
    #[OA\Property(property: 'createdAt', type: 'string', format: 'date-time')]
    #[OA\Property(property: 'updatedAt', type: 'string', format: 'date-time')]
    #[OA\Property(property: 'completedAt', type: 'string', format: 'date-time')]
    #[OA\Property(property: 'subtasks', ref: '#/components/schemas/Task', type: 'array', items: new OA\Items(ref: '#/components/schemas/Task'))]
    protected $fillable = [];
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'status' => TaskStatus::class,
        'completedAt' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parentId')->with('subtasks');
    }
}
