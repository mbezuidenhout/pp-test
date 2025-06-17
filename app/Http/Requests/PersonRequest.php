<?php

namespace App\Http\Requests;

use App\Rules\SouthAfricanID;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $personId = $this->route('person')->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'sa_id_number' => [
                'required',
                'string',
                'size:13',
                Rule::unique('people')->ignore($personId)->where(fn ($q) => $q->whereNull('deleted_at')),
                new SouthAfricanID,
            ],
            'mobile_number' => ['required', 'regex:/^\+\d{1,3}\d{4,14}$/'],
            'email' => ['required', 'string', 'email', 'max:320'], // Maximum length, according to RFC 3696
            'birth_date' => ['required', 'date', 'before:today'],
            'language_code' => ['required', 'string', 'max:35'], // RFC 5646 states the longest character code to be 35 characters
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => 'string|max:255', // Limit to standard MySQL TEXT field, table column is of type longtext
        ];
    }

    public function messages(): array
    {
        return [
            'language_code.required' => 'The language field is required.',
            'birth_date.before' => 'The birth date must be a valid date in the past.',
            'mobile_number.regex' => 'The mobile number must be international format eg. +27123456789.',
        ];
    }
}
