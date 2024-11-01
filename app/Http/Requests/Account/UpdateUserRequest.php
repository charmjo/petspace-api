<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['required','string', 'max:255'],
            'last_name' => ['required','string', 'max:255'],
            'email' => ['required','string', 'max:255','unique'],
            'role' => ['string', 'max:255'],
            'dob' => ['date'], // make sure it is in the format yyyymmdd 
            'gender' => ['string'],
            'phone' => ['required','string'],
            'address_street_name' => ['required','string','max:255'],
            'address_city' => ['required','string','max:255'],
            'address_province' => ['required','string','max:255'],
            'address_country' => ['required','string','max:255'],
            'address_postal_code' => ['required','string','max:255'],
            'is_form_filled' => ['boolean'],
        ];
    }
}
