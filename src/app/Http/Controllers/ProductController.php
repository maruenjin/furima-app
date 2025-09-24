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

    
    $builder = Product::query()->with('user')->latest();

    
    if ($q !== '') {
        $builder->where('name', 'like', "%{$q}%");
    }

    if ($tab === 'mylist') {
        
        if (!auth()->check()) {
            $products = Product::query()
                ->whereRaw('1=0') 
                ->paginate(12)
                ->withQueryString();

            return view('items.index', compact('products', 'tab', 'q'));
        }

        
        $builder->whereHas('likes', fn ($qr) => $qr->whereKey(auth()->id()));
    } else {
       
        if (auth()->check()) {
            $builder->where('user_id', '!=', auth()->id());
        }
    }

    $products = $builder->paginate(12)->withQueryString();
    return view('items.index', compact('products','tab','q'));
}


    public function show(\App\Models\Product $product)
    {
      
        $product->load(['comments.user','likes','seller']);
        $likesCount = $product->likes()->count();
        $commentsCount = $product->comments->count();
        $isLikedByMe = Auth::check() ? $product->isLikedBy(Auth::user()) : false;

        return view('items.show', compact('product','likesCount','commentsCount','isLikedByMe'));  
    }


    public function toggleLike(Product $product)
{
    $userId = auth()->id();
    if (!$userId) {
        return redirect()->route('login');
    }

    
    $product->likes()->toggle([$userId]);

    
    return redirect()->route('products.show', $product);
    }

    public function create()
    {
       $categories = [
        'メンズ','レディース','家電','パソコン','スマホ','インテリア',
        'コスメ','食品','スポーツ','ハンドメイド','おもちゃ','その他',
    ];
       return view('items.sell', compact('categories'));
    }


   public function store(\App\Http\Requests\StoreProductRequest $request)
{
   
    $data = $request->validated();

   
    $data['user_id'] = auth()->id();

   
    $cats = $data['categories'] ?? [];
    if (!is_array($cats)) {
        $cats = array_filter(array_map('trim', explode(',', (string) $cats)));
    }
    $data['categories'] = $cats; 
    if ($request->hasFile('image')) {
        $data['image_path'] = $request->file('image')->store('products', 'public');
    }
    unset($data['image']); 
    $product = \App\Models\Product::create($data);

    return redirect()->route('products.index')->with('status', '出品しました。');
}



}



