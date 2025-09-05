<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductLike;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $q   = trim((string) $request->q);
    $tab = $request->tab === 'mylist' ? 'mylist' : 'all';

    $builder = Product::query()->with('seller')->latest();

    // 検索（商品名：部分一致）
    if ($q !== '') {
        $builder->where('name', 'like', "%{$q}%");
    }

    if ($tab === 'mylist') {
        // 未認証は空表示
        if (!auth()->check()) {
            return view('items.index', [
                'products' => collect(), 'tab' => $tab, 'q' => $q,
            ]);
        }
        // いいねした商品だけ
        $builder->whereHas('likes', fn ($qr) => $qr->where('user_id', auth()->id()));
    } else {
        // 自分が出品した商品は除外
        if (auth()->check()) {
            $builder->where('user_id', '!=', auth()->id());
        }
    }

    $products = $builder->paginate(12)->withQueryString();
    return view('items.index', compact('products','tab','q'));
}

    public function show(\App\Models\Product $product)
    {
      // 件数と「自分がいいね済みか」を渡す
        $product->load(['comments.user','likes','seller']);
        $likesCount = $product->likes()->count();
        $commentsCount = $product->comments->count();
        $isLikedByMe = Auth::check() ? $product->isLikedBy(Auth::user()) : false;

        return view('items.show', compact('product','likesCount','commentsCount','isLikedByMe'));  
    }


    public function toggleLike(Product $product, Request $request)
    {
        $userId = Auth::id();

        $existing = ProductLike::where('user_id',$userId)
                    ->where('product_id',$product->id)
                    ->first();

        if ($existing) {
            $existing->delete();          // いいね解除
        } else {
            $product->likes()->create([
                'user_id' => $userId,     // いいね追加
            ]);
        }

        
        return back();
    }

    public function create()
    {
       $categories = [
        'メンズ','レディース','家電','パソコン','スマホ','インテリア',
        'コスメ','食品','スポーツ','ハンドメイド','おもちゃ','その他',
    ];
       return view('items.sell', compact('categories'));
    }


    public function store(StoreProductRequest $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
        
        $path = $request->file('image')->store('images', 'public');
       }

    
    
       
       $product = \App\Models\Product::create([
        'user_id'     => auth()->id(),
        'name'        => $request->name,
        'brand'       => $request->brand,
        'price'       => $request->price,
        'description' => $request->description,
        'condition'   => $request->condition,
        'image_path'  => $path,                 
        'categories'  => $request->categories,  
    ]);

    return redirect()->route('products.show', $product)->with('status','商品を出品しました。');
}

}



