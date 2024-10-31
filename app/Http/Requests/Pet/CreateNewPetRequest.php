<?php

namespace App\Http\Requests\Pet;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewPetRequest extends FormRequest
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
        // I am using Date datatype for sql for data analytics
        return [
            //
//           'pet_owner_id' => ['required','integer','exists:users,id'],
            'name' => ['required','string'],
            'breed' => ['required','string'],
            'animal_type' => ['required','string'],
            'dob' => ['required','date'],
            'color' => ['required','string'],
            'gender' => ['required','string'],
        ];
    }
}
