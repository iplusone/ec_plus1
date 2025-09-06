<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $q = Order::query()
            ->with('merchant')
            ->latest('id');

        if ($r->filled('status'))  $q->where('status', $r->status);
        if ($r->filled('number'))  $q->where('number','like','%'.$r->number.'%');

        $orders = $q->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items','merchant']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $r, Order $order)
    {
        $data = $r->validate([
            'shipping_status' => ['nullable','string'],
            'tracking_number' => ['nullable','string','max:100'],
            'status'          => ['nullable','string'], // paid, cancelled, refunded など
        ]);
        $order->update($data);
        return back()->with('ok','更新しました');
    }
}
