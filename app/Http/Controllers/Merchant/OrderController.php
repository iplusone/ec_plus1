<?php 

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Merchant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 注文一覧
    public function index(Merchant $merchant, Request $request)
    {
        // （任意）所属チェック: 他社の merchant を弾く
        abort_unless($request->user('seller')  // guard 明示
            ->merchants()->whereKey($merchant->id)->exists(), 403);

        $orders = $merchant->orders()->latest()->paginate(20);
        return view('seller.orders.index', compact('merchant','orders'));
    }



    // 注文詳細
    public function show(Merchant $merchant, Order $order)
    {
        // セキュリティ: 他社の注文を見られないように制約
        abort_unless($order->merchant_id === $merchant->id, 403);

        return view('seller.orders.show', compact('merchant','order'));
    }

    // 出荷ステータス更新
    public function update(Request $request, Merchant $merchant, Order $order)
    {
        abort_unless($order->merchant_id === $merchant->id, 403);

        $data = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,cancelled',
        ]);

        $order->update($data);

        return back()->with('ok','注文ステータスを更新しました');
    }
}
