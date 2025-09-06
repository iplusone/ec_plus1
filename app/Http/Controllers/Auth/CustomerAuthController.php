<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLoginForm(){ return view('auth.customer.login'); }
    public function showRegisterForm(){ return view('auth.customer.register'); }

    public function register(Request $r)
    {
        $data = $r->validate([
            'email' => ['required','email','max:254','unique:customers,email'],
            'name'  => ['nullable','string','max:100'],
            'password' => ['required','string','min:8','confirmed'],
        ]);
        $c = Customer::create([
            'email'=>$data['email'],
            'name' => $data['name'] ?? null,
            'password'=>Hash::make($data['password']),
        ]);

        Auth::guard('customer')->login($c);
        $r->session()->regenerate();
        return redirect()->intended('/');
    }

    public function login(Request $r)
    {
        $cred = $r->validate([
            'email'=>['required','email'],
            'password'=>['required','string'],
        ]);
        if (Auth::guard('customer')->attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['email'=>'ログインに失敗しました'])->onlyInput('email');
    }

    public function logout(Request $r)
    {
        Auth::guard('customer')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('customer.login.form');
    }
}
