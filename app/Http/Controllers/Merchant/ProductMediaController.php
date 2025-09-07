<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends Controller
{
    public function uploadThumbnail(Request $r, Product $product){
        $r->validate(['file'=>['required','image','max:5120']]);
        $path = $r->file('file')->store("products/{$product->id}", 'public');
        $product->update(['thumbnail_path'=>$path]);
        return back()->with('ok','サムネイルを更新しました');
    }

    public function uploadImages(Request $r, Product $product){
        $r->validate(['files.*'=>['required','image','max:5120']]);
        foreach ($r->file('files',[]) as $i=>$file){
            $path = $file->store("products/{$product->id}", 'public');
            ProductImage::create(['product_id'=>$product->id,'path'=>$path,'sort'=>$i]);
        }
        return back()->with('ok','画像を追加しました');
    }


    public function destroyImage(Request $r, Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 403);
        if ($image->path) \Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('ok','画像を削除しました');
    }

    public function destroyThumbnail(Request $r, Product $product)
    {
        if ($product->thumbnail_path) {
            \Storage::disk('public')->delete($product->thumbnail_path);
            $product->update(['thumbnail_path'=>null]);
        }
        return back()->with('ok','サムネイルを削除しました');
    }


}
