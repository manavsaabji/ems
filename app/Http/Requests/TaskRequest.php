<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_1'=>'required|max:255',
            'task_2'=>'required|max:255',
            'task_3'=>'required|max:255',
            'task_4'=>'required|max:255',
            'task_5'=>'required|max:255',
            'task_6'=>'required|max:255',

        ];
    }
}
