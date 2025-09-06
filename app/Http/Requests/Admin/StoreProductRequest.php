<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('product')?->id;
        return [
            'name'        => ['required','string','max:255'],
            'slug'        => ['required','alpha_dash','max:255','unique:products,slug'.($id?",$id":'')],
            'description' => ['nullable','string'],
            'is_active'   => ['boolean'],
        ];
    }

    public function authorize(): bool { return true; }
}
