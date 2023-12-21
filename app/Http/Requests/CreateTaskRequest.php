<?php

namespace App\Http\Requests;

use App\DTO\CreateTaskDTO;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(request: 'CreateTaskRequest', description: 'Create task', required: true, content: new OA\MediaType(mediaType: 'application/x-www-form-urlencoded', schema: new OA\Schema(ref: '#/components/schemas/CreateTaskRequest')))]
#[OA\Schema(schema: 'CreateTaskRequest')]
class CreateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    #[OA\Property(property: 'title', description: 'Task title', type: 'string')]
    #[OA\Property(property: 'description', description: 'Task description', type: 'string')]
    #[OA\Property(property: 'priority', description: 'Task priority (1..5)', type: 'integer', maximum: 5, minimum: 1)]
    #[OA\Property(property: 'parentId', description: 'Parent task ID', type: 'integer')]
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|between:1,5',
            'parentId' => 'nullable|integer|exists:tasks,id',
        ];
    }

    public function toDTO(): CreateTaskDTO
    {
        return new CreateTaskDTO(
            title: $this->input('title'),
            priority: $this->input('priority'),
            description: $this->input('description'),
            parentId: $this->input('parentId'),
        );
    }
}
