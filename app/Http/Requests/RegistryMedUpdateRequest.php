<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistryMedUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'min:8', 'max:11', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'birthdate' => ['required', 'date', 'before:now'],
        ];
    }
}
