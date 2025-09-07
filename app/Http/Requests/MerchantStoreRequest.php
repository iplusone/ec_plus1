<?php 

// app/Http/Requests/MerchantStoreRequest.php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MerchantStoreRequest extends FormRequest {
    public function authorize(): bool { return $this->user()->can('manage-merchants'); }
    public function rules(): array {
        return [
            'name' => ['required','string','max:255'],
            'name_kana' => ['required','string','max:255'],
            'code' => ['nullable','string','max:20','unique:merchants,code'],
            'email' => ['nullable','email','max:100','unique:merchants,email'],
            'phone' => ['nullable','string','max:50'],
            'zip' => ['nullable','string','max:10'],
            'address' => ['nullable','string','max:255'],
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'corporate_number' => ['nullable','string','max:20'],
            'registration_number' => ['nullable','string','max:50'],
            'is_active' => ['required','boolean'],
        ];
    }
}
