<?php

namespace App\Http\Requests\Task;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'due_date' => [
                'required',
                'date',
            ],
            'priority' => [
                'required',
                'string',
                'in:low,medium,high'
            ],
            'title' => [
                'required',
                'string',
                'max:255'
            ]
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(ApiResponse::error(
            message: 'Failed Validation',
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            errors: $validator->errors()->toArray()
        ));
    }
}
