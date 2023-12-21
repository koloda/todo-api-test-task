<?php

namespace App\Http\Requests;

use App\DTO\UpdateTaskDTO;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(request: 'UpdateTaskRequest', description: 'Update task', required: true, content: new OA\MediaType(mediaType: 'application/x-www-form-urlencoded', schema: new OA\Schema(ref: '#/components/schemas/UpdateTaskRequest')))]
#[OA\Schema(schema: 'UpdateTaskRequest')]
class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    #[OA\Property(property: 'title', description: 'Task title', type: 'string')]
    #[OA\Property(property: 'description', description: 'Task description', type: 'string')]
    #[OA\Property(property: 'priority', description: 'Task priority (1..5)', type: 'integer', maximum: 5, minimum: 1)]
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|between:1,5',
        ];
    }

    public function toDTO(): UpdateTaskDTO
    {
        return new UpdateTaskDTO(
            title: $this->input('title'),
            priority: $this->input('priority'),
            description: $this->input('description'),
        );
    }
}
