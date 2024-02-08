<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            "content" => ["max:255"],
            "userId" => ["required"],
            // "media" => ["mimes:jpeg,jpg,webp,png"],
        ];
    }
    protected function prepareForValidation(){
        $this->merge([
            'user_id' => $this->userId,
        ]);
    }
}
