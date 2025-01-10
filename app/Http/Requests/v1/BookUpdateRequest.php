<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'owner' => 'sometimes|string|max:255',
            'about' => 'sometimes|string',
            'category_id' => 'sometimes|nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a string.',
            'owner.string' => 'The owner must be a string.',
            'about.string' => 'The about field must be a string.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Book update failed',
            'errors' => $validator->errors(),
            'status' => 422,
        ], 422));
    }
}
