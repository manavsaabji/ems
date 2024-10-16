<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'addhar_card' => 'file|mimes:pdf|max:2048',
            'pan_card' => 'file|mimes:pdf|max:2048',
            'bank_document' => 'file|mimes:pdf|max:2048',
        ];
    }
}
