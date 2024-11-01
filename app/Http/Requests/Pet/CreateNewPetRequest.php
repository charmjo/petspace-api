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
            'name' => ['required','string','max:255'],
            'breed' => ['required','string','max:255'],
            'animal_type' => ['required','string','max:255'],
            'dob' => ['required','date'],
            'color' => ['required','string','max:100'],
            'gender' => ['required','string','max:100'],
            'bio' => ['string','max:100']
        ];
    }
}
