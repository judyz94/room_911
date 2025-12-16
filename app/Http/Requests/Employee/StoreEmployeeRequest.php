<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'internal_id' => 'required|string|unique:employees,internal_id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'has_access' => 'boolean'
        ];
    }
}
