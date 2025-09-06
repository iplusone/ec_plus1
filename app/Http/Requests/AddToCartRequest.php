<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'variant_id' => ['required','integer','exists:product_variants,id'],
            'qty'        => ['required','integer','min:1','max:999'],
        ];
    }
    public function authorize(): bool { return true; }
}
