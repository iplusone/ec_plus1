<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

//
// /admin/login でメールアドレスを間違えて連続で送信すると、6回目以降は HTTP 429 Too Many Requests が返る。
//
// 1分経つとリセットされ、再びログイン可能。
//
class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // login レートリミタを定義
        RateLimiter::for('login', function (Request $request) {
            // ユーザーの email + IP ごとにカウント
            $key = $request->input('email').'|'.$request->ip();

            return [
                Limit::perMinute(5)->by($key), // 1分間に最大5回
            ];
        });
    }
}
