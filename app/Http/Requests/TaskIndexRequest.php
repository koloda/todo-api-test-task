<?php

namespace App\Http\Requests;

use App\DTO\TaskIndexFilterDTO;
use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'string', Rule::in([TaskStatus::Todo->value, TaskStatus::Done->value])],
            'priority' => ['nullable', 'integer', 'min:1', 'max:5'],
            'text' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'string'],
        ];
    }

    public function toDTO(): TaskIndexFilterDTO
    {
        $status = $this->input('status')
            ? TaskStatus::from($this->input('status'))
            : null;

        if ($this->input('order')) {
            $orderOptions = array_map(
                fn (string $option) => TaskOrder::from($option),
                explode(',', $this->input('order'))
            );
        }

        return new TaskIndexFilterDTO(
            status: $status,
            priority: $this->input('priority'),
            text: $this->input('text'),
            order: $orderOptions ?? null,
        );
    }
}
