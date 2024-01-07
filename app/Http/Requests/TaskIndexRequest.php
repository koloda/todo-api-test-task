<?php

namespace App\Http\Requests;

use App\DTO\TaskIndexFilterDTO;
use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\RequestBody(request: 'TaskIndexRequest', description: 'Task index filter', required: false, content: new OA\MediaType(mediaType: 'application/x-www-form-urlencoded', schema: new OA\Schema(ref: '#/components/schemas/TaskIndexRequest')))]
#[OA\Schema(schema: 'TaskIndexRequest')]
class TaskIndexRequest extends FormRequest
{
    private ?array $orderOptions;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    #[OA\Property(property: 'status', description: 'Filter parameter for status(todo, done)', type: 'string', enum: [TaskStatus::Todo, TaskStatus::Done])]
    #[OA\Property(property: 'priority', description: 'Filter parameter for priority (1..5)', type: 'integer', maximum: 5, minimum: 1)]
    #[OA\Property(property: 'text', description: 'Filter parameter for text (search in title and description)', type: 'string')]
    #[OA\Property(property: 'order', description: 'Order parameter (priority.asc, priority.desc, createdAt.asc, createdAt.desc, completedAt.asc, completedAt.desc). Possible to use 2 order params separated by comma, like: order=priority.asc,completedAt.desc', type: 'string')]
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in([TaskStatus::Todo->value, TaskStatus::Done->value])],
            'priority' => ['nullable', 'integer', 'min:1', 'max:5'],
            'text' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!$this->validateOrder()) {
                    $fail('Order parameter is invalid');
                }
            }],
        ];
    }

    public function toDTO(): TaskIndexFilterDTO
    {
        $status = $this->input('status')
            ? TaskStatus::from($this->input('status'))
            : null;

        return new TaskIndexFilterDTO(
            status: $status,
            priority: $this->input('priority'),
            text: $this->input('text'),
            order: $this->orderOptions ?? null,
        );
    }

    public function validateOrder(): bool
    {
        if ($this->input('order')) {
            try {
                $this->orderOptions = array_map(
                    fn (string $option) => TaskOrder::from($option),
                    explode(',', $this->input('order'))
                );
            } catch (\ValueError $e) {
                return false;
            }
        }

        return true;
    }
}
