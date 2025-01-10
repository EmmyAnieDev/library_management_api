<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:categories,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The category name must be a string.',
            'name.unique' => 'The category name already exists.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Category update failed',
            'errors' => $validator->errors(),
            'status' => 422,
        ], 422));
    }
}
