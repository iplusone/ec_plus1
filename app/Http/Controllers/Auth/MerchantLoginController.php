<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class MerchantLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.merchant.login');
    }

    public function login(Request $r)
    {
        $cred = $r->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);
        if (Auth::guard('merchant')->attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(route('merchant.home'));
        }
        return back()->withErrors(['email'=>'メールまたはパスワードが違います'])->onlyInput('email');
    }

    public function logout(Request $r)
    {
        Auth::guard('merchant')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('merchant.login.form');
    }
}
