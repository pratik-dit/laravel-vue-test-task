<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
          'name' => ['required', 'string'],
          'price' => ['required', 'numeric'],
          'category_id' => ['required', 'numeric'],
          'description' => ['nullable', 'string'],
          'is_active' => ['required', 'numeric'],
        ];
    }
}