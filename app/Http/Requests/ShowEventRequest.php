<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'showId' => ['required', 'regex:/^\d+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'showId.regex' => 'Неверный формат данных',
            'showId.required' => 'Не передан id события',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'showId' => $this->route('showId'),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Ошибка валидации',
            'errors' => $validator->errors(),
        ], 422));
    }
}
