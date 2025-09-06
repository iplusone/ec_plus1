<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required','email:rfc,dns','max:254'],
            'name'  => ['nullable','string','max:100'],
        ];
    }
    public function authorize(): bool { return true; }
}
