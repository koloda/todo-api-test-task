<?php

namespace App\Http\Requests;

use App\DTO\UpdateTaskDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
