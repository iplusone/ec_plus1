<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerchantController extends Controller
{
    // 検索一覧
    public function index(Request $r)
    {
        $kw    = trim((string) $r->input('kw'));
        $active = $r->filled('active') ? (int)$r->input('active') : null; // 0/1 or null

        $q = Merchant::query()
            ->when($kw !== '', function ($qq) use ($kw) {
                $like = "%{$kw}%";
                $qq->where(function ($w) use ($like) {
                    $w->where('name', 'like', $like)
                      ->orWhere('name_kana', 'like', $like)
                      ->orWhere('code', 'like', $like)
                      ->orWhere('email', 'like', $like);
                });
            })
            ->when(!is_null($active), fn ($qq) => $qq->where('is_active', $active))
            ->orderByDesc('id');

        $merchants = $q->paginate(20)->withQueryString();

        return view('admin.merchants.index', compact('merchants', 'kw', 'active'));
    }

    public function create()
    {
        $merchant = new Merchant(['is_active' => true]);
        return view('admin.merchants.create', compact('merchant'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'                => ['required','string','max:255'],
            'name_kana'           => ['required','string','max:255'], // DBはNOT NULL
            'code'                => ['nullable','string','max:20','unique:merchants,code'],
            'email'               => ['nullable','email','max:100'],  // 必要なら unique:merchants,email に
            'phone'               => ['nullable','string','max:50'],
            'zip'                 => ['nullable','string','max:10'],
            'address'             => ['nullable','string','max:255'],
            'lat'                 => ['nullable','numeric'],
            'lng'                 => ['nullable','numeric'],
            'corporate_number'    => ['nullable','string','max:20'],
            'registration_number' => ['nullable','string','max:50'],
            'is_active'           => ['nullable','boolean'],
        ]);

        // 未送信なら true に倒す（運用で無効開始にしたければ 0 に変えるだけ）
        $data['is_active'] = array_key_exists('is_active', $data) ? (bool)$data['is_active'] : true;

        $merchant = Merchant::create($data);

        return redirect()
            ->route('admin.merchants.edit', $merchant)
            ->with('ok', '販売会社を登録しました');
    }

    public function edit(Merchant $merchant)
    {
        return view('admin.merchants.edit', compact('merchant'));
    }

    public function update(Request $r, Merchant $merchant)
    {
        $data = $r->validate([
            'name'                => ['required','string','max:255'],
            'name_kana'           => ['required','string','max:255'],
            'code'                => ['nullable','string','max:20', Rule::unique('merchants','code')->ignore($merchant->id)],
            'email'               => ['nullable','email','max:100'], // 必要なら Rule::unique(...)->ignore($merchant->id)
            'phone'               => ['nullable','string','max:50'],
            'zip'                 => ['nullable','string','max:10'],
            'address'             => ['nullable','string','max:255'],
            'lat'                 => ['nullable','numeric'],
            'lng'                 => ['nullable','numeric'],
            'corporate_number'    => ['nullable','string','max:20'],
            'registration_number' => ['nullable','string','max:50'],
            'is_active'           => ['nullable','boolean'],
        ]);

        $data['is_active'] = array_key_exists('is_active', $data) ? (bool)$data['is_active'] : $merchant->is_active;

        $merchant->update($data);

        return back()->with('ok', '販売会社を更新しました');
    }

    // 物理削除せず、停止/再開トグル
    public function toggle(Merchant $merchant)
    {
        $merchant->update(['is_active' => ! $merchant->is_active]);
        return back()->with('ok', $merchant->is_active ? '契約を再開しました' : '契約を停止しました');
    }

    // destroyは封印
    public function destroy(Merchant $merchant) { abort(404); }
}
