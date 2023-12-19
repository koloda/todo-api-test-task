<?php

namespace App\Http\Requests;

use App\DTO\CreateTaskDTO;
use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|between:1,5',
            'status' => 'required|string|in:todo,done',
            'parentId' => 'nullable|integer|exists:tasks,id',
        ];
    }

    public function toDTO(): CreateTaskDTO
    {
        return new CreateTaskDTO(
            title: $this->input('title'),
            priority: $this->input('priority'),
            status: TaskStatus::from($this->input('status')),
            description: $this->input('description'),
            parentId: $this->input('parentId'),
        );
    }
}
