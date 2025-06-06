<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventReserveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'eventId' => ['required', 'regex:/^\d+$/'],
            'name' => ['required', 'string'],
            'places' => ['required', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'eventId.regex' => 'Неверный формат данных',
            'eventId.required' => 'Не передан id события',
            'name.required' => 'Имя обязательно',
            'places.required' => 'Места для бронирования обязательны',
            'places.array' => 'Неверный формат данных мест',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'eventId' => $this->route('eventId'),
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
