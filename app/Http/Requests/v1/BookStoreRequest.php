<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'about' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The book name is required.',
            'owner.required' => 'The book owner is required.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Book creation failed',
            'errors' => $validator->errors(),
            'status' => 422,
        ], 422));
    }
}
