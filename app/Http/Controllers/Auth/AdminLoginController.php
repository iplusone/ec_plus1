<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminLoginController extends Controller
{

    public function showLoginForm() {
        Config::set('auth.defaults.guard', 'admin');   // 念押し
        return view('auth.admin.login');
    }

    public function login(Request $r) {
        Config::set('auth.defaults.guard', 'admin');   // 念押し
        $cred = $r->validate(['email'=>'required|email','password'=>'required']);
        if (Auth::guard('admin')->attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(route('admin.home'));
        }
        return back()->withErrors(['email'=>'認証に失敗しました'])->onlyInput('email');
    }

    public function logout(Request $r) {
        Config::set('auth.defaults.guard', 'admin');   // 念押し
        Auth::guard('admin')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('admin.login.form');
    }
}
