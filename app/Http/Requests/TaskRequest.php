<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:191',
            ],
            'description' => [
                'required',
                'string',
                'max:16000',
            ],
            'is_completed' => [
                'required',
                'boolean',
            ],
        ];
    }
}