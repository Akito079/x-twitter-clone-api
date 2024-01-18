<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateeCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        if($method == "PUT") {
            return [
                "userId" => ["sometimes","required"],
                "postId" => ["required"],
                "commentContent" => ["required"],
            ];
        }else{
            return [
                "userId" => ["sometimes","required"],
                "postId" => ["sometimes","required"],
                "commentContent" => ["sometimes","required"],
            ];
        }

    }
    protected function prepareForValidation(){
        $this->merge([
            "user_id" => $this->userId,
            "post_id" => $this->postId,
            "comment_content" => $this->comment_content,
        ]);
    }
}
