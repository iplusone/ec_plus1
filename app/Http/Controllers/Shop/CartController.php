<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Cart, CartItem, ProductVariant};
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show() {
        $m = app('merchant');
        $cart = Cart::firstOrCreate(['id'=>session()->getId()], ['merchant_id'=>$m->id,'currency'=>'JPY']);
        $items = $cart->items()->with('variant.product')->get();
        $total = $items->sum(fn($i)=> $i->qty * $i->price_amount);
        return view('shop.cart.show', compact('cart','items','total'));
    }

    public function add(Request $r) {
        $m = app('merchant');
        $data = $r->validate([
            'variant_id'=>['required','exists:product_variants,id'],
            'qty'=>['required','integer','min:1','max:99'],
        ]);
        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        abort_unless($variant->product->merchant_id === $m->id, 403);

        $cart = Cart::firstOrCreate(['id'=>session()->getId()], ['merchant_id'=>$m->id,'currency'=>'JPY']);
        $item = $cart->items()->firstOrNew(['product_variant_id'=>$variant->id], [
            'product_id'=>$variant->product_id,
            'price_amount'=>$variant->price_amount,
        ]);
        $item->qty += $data['qty'];
        $item->save();

        return redirect()->route('cart.show', ['merchant'=>$m->slug])->with('ok','カートに追加しました');
    }

    public function update(Request $r) {
        $m = app('merchant');
        $r->validate(['items'=>'required|array']);
        $cart = Cart::where('id',session()->getId())->firstOrFail();
        abort_unless($cart->merchant_id === $m->id, 400);
        foreach ($r->items as $id=>$qty) {
            $ci = $cart->items()->find($id);
            if (!$ci) continue;
            $q = max(0, min(99, (int)$qty));
            if ($q===0) $ci->delete(); else { $ci->qty=$q; $ci->save(); }
        }
        return back()->with('ok','更新しました');
    }

    public function remove(Request $r) {
        $m = app('merchant');
        $id = $r->validate(['id'=>'required|integer'])['id'];
        $cart = Cart::where('id',session()->getId())->firstOrFail();
        abort_unless($cart->merchant_id === $m->id, 400);
        $cart->items()->where('id',$id)->delete();
        return back()->with('ok','削除しました');
    }
}
