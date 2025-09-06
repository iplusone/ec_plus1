<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('variant')?->id;
        return [
            'product_id'   => ['required','exists:products,id'],
            'sku'          => ['required','string','max:64','unique:product_variants,sku'.($id?",$id":'')],
            'price_amount' => ['required','integer','min:0'],
            'currency'     => ['required','string','size:3'],
            'stock'        => ['required','integer','min:0'],
        ];
    }

    public function authorize(): bool { return true; }
}
