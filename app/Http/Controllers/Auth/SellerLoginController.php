<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerLoginController extends Controller
{
    public function showLoginForm() { return view('auth.seller.login'); }

    public function login(Request $r)
    {
        $cred = $r->validate([
            'email'=>['required','email'],
            'password'=>['required','string'],
        ]);

        if (Auth::guard('seller')->attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate();
            // ログイン後は seller ホーム（merchant は個別に選択 or 直リンク）
            return redirect()->intended('/seller');
        }
        return back()->withErrors(['email'=>'ログインに失敗しました'])->onlyInput('email');
    }

    public function logout(Request $r)
    {
        Auth::guard('seller')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('seller.login.form');
    }
}
